<?php

namespace App\Tests\Staff\Care;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListingCareTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}