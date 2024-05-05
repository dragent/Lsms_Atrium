<?php

namespace App\Tests\Staff;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IndexTest extends WebTestCase
{
    /**
     * Verifie si une personne non connectée peut accéder à la page admin
     */
    public function testAnonymousCanAccessPage(): void
    {
        $client = self::createClient();
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff');
        $client->request('GET',$url);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/connexion', $client->getResponse()->headers->get('Location'));
    }
}
