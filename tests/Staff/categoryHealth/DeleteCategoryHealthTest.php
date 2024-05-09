<?php

namespace App\Tests\Staff\categoryHealth;

use App\Repository\CategoryHealthRepository;
use App\Tests\Factory\CategoryHealthFactory;
use Faker\Factory;
use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DeleteCategoryHealthTest extends WebTestCase
{
/**
     * Vérifie que l'on ne puisse pas aller sur un produit non existant
     */
    public function testCantAccessObjextNonExisting(): void
    {
        $factory = new Factory();
        $client = self::createClient();        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_category_health_delete',["slug"=> $factory->create()->slug()]);
        $client->request('GET',$url);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/connexion', $client->getResponse()->headers->get('Location'));
    }
    /**
     * Vérifie que l'on ne puisse pas supprimer sans être connecté
     */
    public function testIsAnonymousRedirected(): void
    {
        $object = CategoryHealthFactory::createOne();
        $client = self::createClient();        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_category_health_delete',["slug"=>$object->object()->getSlug()]);
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
        $category = CategoryHealthFactory::createOne();
        $client = self::createClient();
        $user = UserFactory::createOne();
        $client->loginUser($user->object());
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_category_health_delete',["slug"=>$category->object()->getSlug()]);
        $client->request('GET',$url);
        $user->remove();
        $category->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/', $client->getResponse()->headers->get('Location'));
    }
    
    /**
     * Etre sur que l'utilisateur ne peut aller sur la page s'il n'est pas admin
     */
    public function testIsAdminConnected()
    {
        $category = CategoryHealthFactory::createOne();
        $client = self::createClient();
        $user = UserFactory::createOne();
        $user->object()->setRoles(['ROLE_STAFF']);
        $user->save();
        $client->loginUser($user->object());        
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_category_health_delete',["slug"=>$category->object()->getSlug()]);
        $client->request('GET',$url);
        $user->remove();
        $category->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
  /**
     * Etre sur que l'utilisateur ne peut aller sur la page si la chambre n'existe pas
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
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_category_health_delete',["slug"=> $factory->create()->slug()]);
        $client->request('GET',$url);
        $user->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/admin/categorie-soins', $client->getResponse()->headers->get('Location'));
    }

    /**
     * Etre sur que l'utilisateur ne peut aller sur la page si la chambre n'existe pas
     */
    public function testCategoryDeleted()
    {
        $client = self::createClient();
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $categoryHealthRepository = self::getContainer()->get(CategoryHealthRepository::class);
        $category = CategoryHealthFactory::createOne();
        $user = UserFactory::createOne();
        $client->loginUser($user->object());
        $url= "https://127.0.0.1:8000".$urlGenerator->generate('app_staff_category_health_delete',["slug"=> $category->object()->getSlug()]);
        $client->request('GET',$url);
        $user->remove();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertNull($categoryHealthRepository->find($category->object()->getSlug()));
        $category->remove();
    }
}
