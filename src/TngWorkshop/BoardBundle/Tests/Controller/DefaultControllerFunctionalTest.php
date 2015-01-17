<?php

namespace TngWorkshop\BoardBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/** @group integration */
class DefaultControllerFunctionalTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testNotFound() {
        $client = static::createClient();

        $client->request('GET', '/does/not/exist');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
