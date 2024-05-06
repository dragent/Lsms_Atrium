<?php

namespace App\Tests\Staff\Staff;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ListTest extends WebTestCase
{
    /**
     * Etre sur que l'utilisateur ne peut aller sur la page sans se connecter
     */
    public function testSomething(): void
    {
        $client = self::createClient();        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_staff_list');
        $client->request('GET',$url);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/connexion', $client->getResponse()->headers->get('Location'));
    }
}
