<?php

namespace App\Tests\Staff\Staff;

use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ListTest extends WebTestCase
{
    /**
     * Etre sur que l'utilisateur ne peut aller sur la page sans se connecter
     */
    public function testIsAnonymousRedirected(): void
    {
        $client = self::createClient();        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_staff_list');
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
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_object');
        $client->request('GET',$url);
        $user->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
