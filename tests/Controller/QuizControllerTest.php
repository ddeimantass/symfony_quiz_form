<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuizControllerTest extends WebTestCase
{
    public function testNew()
    {
        $client = static::createClient();
        
        $client->request('GET', '/quiz/new');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}