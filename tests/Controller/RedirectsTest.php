<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedirectsTest extends WebTestCase
{
    public function testFavicon(): void
    {
        $client = static::createClient();
        $client->request('GET', '/favicon.ico');
        $response = $client->getResponse();

        $this->assertResponseRedirects();
        $this->assertResponseHasHeader('Location');
        $this->assertMatchesRegularExpression('#/assets/favicon.*\.ico#i', $response->headers->get('Location'));
    }
}
