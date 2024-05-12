<?php

namespace App\Tests\Staff\Care;

use Faker\Factory;
use App\Tests\Factory\CareFactory;
use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class ModifyCareTest extends WebTestCase
{

   /**
     * Vérifie que l'on ne puisse pas aller sur un produit non existant
     */
    public function testCantAccessObjextNonExisting(): void
    {
        $factory = new Factory();
        $client = self::createClient();        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_care',["slug"=> $factory->create()->slug()]);
        $client->request('GET',$url);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/connexion', $client->getResponse()->headers->get('Location'));
    }

    /**
     * Vérifie que l'on ne puisse pas supprimer sans être connecté
     */
    public function testIsAnonymousRedirected(): void
    {
        $care = CareFactory::createOne();
        $client = self::createClient();        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_care',["slug"=>$care->object()->getSlug()]);
        $client->request('GET',$url);
        $care->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/connexion', $client->getResponse()->headers->get('Location'));
    }

    /**
     * Etre sur que l'utilisateur ne peut aller sur la page s'il n'est pas admin
     */
    public function testIsNotAdminRedirected()
    {
        $care = CareFactory::createOne();
        $client = self::createClient();
        $user = UserFactory::createOne();
        $client->loginUser($user->object());
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_care',["slug"=>$care->object()->getSlug()]);
        $client->request('GET',$url);
        $user->remove();
        $care->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/', $client->getResponse()->headers->get('Location'));
    }

    /**
     * Etre sur que l'utilisateur ne peut aller sur la page s'il n'est pas admin
     */
    public function testIsAdminConnected()
    {
        $care = CareFactory::createOne();
        $client = self::createClient();
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_care',["slug"=>$care->object()->getSlug()]);
        $client->request('GET',$url);
        $user->remove();
        $care->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
        /**
     * Test modification des soins
     */
    public function testModifyObject()
    {
        $client = self::createClient();
        $care = CareFactory::createOne();
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_care_modify',["slug"=>$care->object()->getSlug()]);
        $crawler = $client->request('POST',$url);
        $form = $crawler->filter('form')->form();
        $price = $care->object()->getPrice();
        $form->setValues(["modify_care[price]" => $care->object()->getPrice()-1]);
        $this->getClient()->submit($form);
        $care->refresh();
        $user->remove();
        $this->assertNotEquals($price,$care->object()->getPrice());
        $care->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/admin/soin', $client->getResponse()->headers->get('Location'));
    }
}
