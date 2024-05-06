<?php

namespace App\Tests\Staff\Object;

use App\Repository\ObjectsRepository;
use App\Tests\Factory\ObjectsFactory;
use App\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddTest extends WebTestCase
{
 /**
     * Etre sur que l'utilisateur ne peut aller sur la page sans se connecter
     */
    public function testIsAnonymousRedirected(): void
    {
        $client = self::createClient();        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_object_add');
        $client->request('GET',$url);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/connexion', $client->getResponse()->headers->get('Location'));
    }


    /**
     * Etre sur que l'utilisateur ne peut aller sur la page s'il n'est pas admin
     */
    public function testIsNotAdminRedirected()
    {
        $client = self::createClient();
        $user = UserFactory::createOne();
        $client->loginUser($user->object());
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_object_add');
        $client->request('GET',$url);
        $user->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/', $client->getResponse()->headers->get('Location'));
    }

    /**
     * Etre sur que l'utilisateur ne peut aller sur la page s'il n'est pas admin
     */
    public function testIsAdminConnected()
    {
        $client = self::createClient();
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_object_add');
        $client->request('GET',$url);
        $user->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

      /**
     * Etre sur que l'utilisateur ne peut aller sur la page s'il n'est pas admin
     */
    public function testCreateObject()
    {
        $client = self::createClient();
        $objectsRepository = $this->getContainer()->get(ObjectsRepository::class);
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $objectArray=  ObjectsFactory::createArray();
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_object_add');
        $crawler = $client->request('POST',$url,[""]);
        $form = $crawler->filter('form')->form();
        $form->setValues(["add_object[name]" => $objectArray['name']]);
        $form->setValues(["add_object[quantity]" => $objectArray['quantity']]);
        $form->setValues(["add_object[quantityTrigger]" => $objectArray['quantityTrigger']]);
        $this->getClient()->submit($form);
        $user->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $object= $objectsRepository->findOneBy(["slug"=>$objectArray["slug"]]);
        $this->assertNotNull($object);
        $em->remove($object);
        $em->flush();
    }

}
