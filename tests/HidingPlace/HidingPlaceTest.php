<?php

namespace App\Tests\HidingPlace;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class HidingPlaceTest extends WebTestCase
{
    public function testCreateHidingPlace(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_hidingPlace'));

        $form = $crawler->filter("form[name=hiding_place]")->form([
            "hiding_place[address]" => "29 rue de Paris",
            "hiding_place[city]" => "Paris",
            "hiding_place[postalCode]" => "75126",
            "hiding_place[type]" => "Maison",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
