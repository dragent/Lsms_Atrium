<?php

namespace App\Tests\Staff\Chamber;

use Faker\Factory;
use App\Tests\Factory\UserFactory;
use App\Tests\Factory\ChamberFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ModifyChamberTest extends WebTestCase
{
   /**
     * Vérifie que l'on ne puisse pas aller sur un produit non existant
     */
    public function testCantAccessObjextNonExisting(): void
    {
        $factory = new Factory();
        $client = self::createClient();        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_chamber_modify',["slug"=> $factory->create()->slug()]);
        $client->request('GET',$url);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/connexion', $client->getResponse()->headers->get('Location'));
    }

    /**
     * Vérifie que l'on ne puisse pas supprimer sans être connecté
     */
    public function testIsAnonymousRedirected(): void
    {
        $object = ChamberFactory::createOne();
        $client = self::createClient();        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_chamber_modify',["slug"=>$object->object()->getSlug()]);
        $client->request('GET',$url);
        $object->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/connexion', $client->getResponse()->headers->get('Location'));
    }

    /**
     * Etre sur que l'utilisateur ne peut aller sur la page s'il n'est pas admin
     */
    public function testIsNotAdminRedirected()
    {
        $object = ChamberFactory::createOne();
        $client = self::createClient();
        $user = UserFactory::createOne();
        $client->loginUser($user->object());
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_chamber_modify',["slug"=>$object->object()->getSlug()]);
        $client->request('GET',$url);
        $user->remove();
        $object->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/', $client->getResponse()->headers->get('Location'));
    }

    /**
     * Etre sur que l'utilisateur ne peut aller sur la page s'il n'est pas admin
     */
    public function testIsAdminConnected()
    {
        $chamber = ChamberFactory::createOne();
        $client = self::createClient();
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_chamber_modify',["slug"=>$chamber->object()->getSlug()]);
        $client->request('GET',$url);
        $user->remove();
        $chamber->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

        /**
     * Test modification de l'objet
     */
    public function testModifyObject()
    {
        $client = self::createClient();
        $chamber = ChamberFactory::createOne();
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_chamber_modify',["slug"=>$chamber->object()->getSlug()]);
        $crawler = $client->request('POST',$url);
        $form = $crawler->filter('form')->form();
        $price = $chamber->object()->getPrice();
        $form->setValues(["modify_chamber[price]" => $chamber->object()->getPrice()-1]);
        $this->getClient()->submit($form);
        $chamber->refresh();
        $user->remove();
        $this->assertNotEquals($price,$chamber->object()->getPrice());
        $chamber->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/admin/chambre', $client->getResponse()->headers->get('Location'));
    }
}
