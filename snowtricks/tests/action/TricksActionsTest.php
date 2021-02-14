<?php

namespace App\Tests\action;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TricksActionsTest extends WebTestCase
{
    public function testGetHomePage(): void
    {
        $client = static::createClient();
        $client->insulate();
        $client->catchExceptions(true);
        $client->request('GET','/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testFeedTricks(): void
    {
        $client = static::createClient();
        $client->insulate();
        $client->catchExceptions(true);
        $client->request('GET','/feed/8/tricks');
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testGetShowTrickPage(): void
    {
        $client = static::createClient();
        $client->insulate();
        $client->catchExceptions(true);
        $client->request('GET','/trick/8');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testGetCreateTrickPage(): void
    {
        $client = static::createClient();
        $client->insulate();
        $client->catchExceptions(true);
        $client->request('GET','/create/trick');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
}
