<?php

namespace App\Tests\Staff\categoryHealth;

use App\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryHealthRepository;
use App\Tests\Factory\CategoryHealthFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddCategoryHealthTest extends WebTestCase
{
 /**
         * Etre sur que l'utilisateur ne peut aller sur la page sans se connecter
         */
        public function testIsAnonymousRedirected(): void
        {
            $client = self::createClient();        
            $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
            $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_category_health_add');
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
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_category_health_add');
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
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_category_health_add');
        $client->request('GET',$url);
        $user->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

      /**
     * Etre sur que l'utilisateur peut créer une categorie
     */
    public function testCreateCategory()
    {
        $client = self::createClient();
        $categoryRepository = $this->getContainer()->get(CategoryHealthRepository::class);
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $objectArray=  CategoryHealthFactory::createArray();
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_category_health_add');
        $crawler = $client->request('POST',$url,[""]);
        $form = $crawler->filter('form')->form();
        $form->setValues(["add_category_health[name]" => $objectArray['name']]);
        $this->getClient()->submit($form);
        $user->remove();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $category= $categoryRepository->findOneBy(["slug"=>$objectArray["slug"]]);
        $this->assertNotNull($category);
        $em->remove($category);
        $em->flush();
    }
}
