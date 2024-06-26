<?php

namespace App\Tests\Staff\Chamber;

use App\Tests\Factory\UserFactory;
use App\Repository\ChamberRepository;
use App\Tests\Factory\ChamberFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddChamberTest extends WebTestCase
{
        /**
         * Etre sur que l'utilisateur ne peut aller sur la page sans se connecter
         */
        public function testIsAnonymousRedirected(): void
        {
            $client = self::createClient();        
            $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
            $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_chamber_add');
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
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_chamber_add');
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
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_chamber_add');
        $client->request('GET',$url);
        $user->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

      /**
     * Etre sur que l'utilisateur peut créer une chambre
     */
    public function testCreateChamber()
    {
        $client = self::createClient();
        $objectsRepository = $this->getContainer()->get(ChamberRepository::class);
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $objectArray=  ChamberFactory::createArray();
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_chamber_add');
        $crawler = $client->request('POST',$url,[""]);
        $form = $crawler->filter('form')->form();
        $form->setValues(["add_chamber[name]" => $objectArray['name']]);
        $form->setValues(["add_chamber[type]" => $objectArray['type']]);
        $form->setValues(["add_chamber[price]" => $objectArray['price']]);
        $this->getClient()->submit($form);
        $user->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $object= $objectsRepository->findOneBy(["slug"=>$objectArray["slug"]]);
        $this->assertNotNull($object);
        $em->remove($object);
        $em->flush();
    }
}
