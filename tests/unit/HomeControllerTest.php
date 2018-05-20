<?php

namespace App\Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testRootIsOnPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

//        echo $crawler->html(); //only root element is found?

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('#root')->count());
        #root > div > div.card > div.card__side.card__side--front > div > div.col-6.col--text > div > a
    }
}
