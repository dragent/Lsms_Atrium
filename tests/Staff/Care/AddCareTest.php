<?php

namespace App\Tests\Staff\Care;

use App\Entity\Care;
use App\Repository\CareRepository;
use App\Tests\Factory\CareFactory;
use App\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddCareTest extends WebTestCase
{
 /**
     * Etre sur que l'utilisateur ne peut aller sur la page sans se connecter
     */
    public function testIsAnonymousRedirected(): void
    {
        $client = self::createClient();
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_care_add');
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
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_care_add');
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
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_care_add');
        $client->request('GET',$url);
        $user->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    /**
     * Etre sur que l'utilisateur peut créer un soin
     */
    public function testCreateCare()
    {
        $client = self::createClient();
        $careRepository = $this->getContainer()->get(CareRepository::class);
        $careRepository = $this->getContainer()->get(CareRepository::class);
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $objectArray=  CareFactory::createArray();
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_care_add');
        $crawler = $client->request('POST',$url,[""]);
        $form = $crawler->filter('form')->form();
        $form->setValues(["add_care[name]" => $objectArray['name']]);
        $form->setValues(["add_care[price]" => $objectArray['price']]);
        $form->setValues(["add_care[category]" => $objectArray['category']->getId()]);
        $this->getClient()->submit($form);
        $user->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $care= $careRepository->findOneBy(["slug"=>$objectArray["slug"]]);
        $healthcare = $careRepository->find($care->getId());
        $this->assertNotNull($care);
        $em->remove($care);
        $em->remove($healthcare);
        $em->flush();
    }
}
