<?php

namespace App\Tests\Staff\Object;

use Faker\Factory;
use App\Tests\Factory\UserFactory;
use App\Repository\ObjectsRepository;
use App\Tests\Factory\ObjectsFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ModiftTest extends WebTestCase
{
   /**
     * Vérifie que l'on ne puisse pas aller sur un produit non existant
     */
    public function testCantAccessObjextNonExisting(): void
    {
        $factory = new Factory();
        $client = self::createClient();        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_object_modify',["slug"=> $factory->create()->slug()]);
        $client->request('GET',$url);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/connexion', $client->getResponse()->headers->get('Location'));
    }

      /**
     * Vérifie que l'on ne puisse pas supprimer sans être connecté
     */
    public function testIsAnonymousRedirected(): void
    {
        $object = ObjectsFactory::createOne();
        $client = self::createClient();        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_object_modify',["slug"=>$object->object()->getSlug()]);
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
        $object = ObjectsFactory::createOne();
        $client = self::createClient();
        $user = UserFactory::createOne();
        $client->loginUser($user->object());
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_object_modify',["slug"=>$object->object()->getSlug()]);
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
        $object = ObjectsFactory::createOne();
        $client = self::createClient();
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_object_modify',["slug"=>$object->object()->getSlug()]);
        $client->request('GET',$url);
        $user->remove();
        $object->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Etre sur que l'utilisateur ne peut aller sur la page si le produit n'existe pas
     */
    public function testObjectNonExistingAdminError()
    {
        $factory = new Factory();
        $client = self::createClient();
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_object_modify',["slug"=>$factory->create()->slug()]);
        $client->request('GET',$url);
        $user->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals($urlGenerator->generate('app_staff_object'), $client->getResponse()->headers->get('Location'));
    }

        /**
     * Etre sur que l'utilisateur ne peut aller sur la page s'il n'est pas admin
     */
    public function testModifyObject()
    {
        $client = self::createClient();
        $object = ObjectsFactory::createOne();
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_object_modify',["slug"=>$object->object()->getSlug()]);
        $crawler = $client->request('POST',$url);
        $form = $crawler->filter('form')->form();
        $quantity = $object->object()->getQuantity();
        $quantityTrigger = $object->object()->getQuantityTrigger();
        $form->setValues(["modify_object[quantity]" => $object->object()->getQuantity()+1]);
        $form->setValues(["modify_object[quantityTrigger]" => $object->object()->getQuantityTrigger()-1]);
        $this->getClient()->submit($form);
        $object->refresh();
        $this->assertNotEquals($quantity,$object->object()->getQuantity());
        $this->assertNotEquals($quantityTrigger,$object->object()->getQuantityTrigger());
        $user->remove();
        $object->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
  

}
