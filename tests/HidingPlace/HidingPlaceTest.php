<?php

namespace App\Tests\HidingPlace;

use App\Entity\HidingPlace;
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

    public function testCreateHidingPlaceForEdit(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_hidingPlace'));

        $form = $crawler->filter("form[name=hiding_place]")->form([
            "hiding_place[address]" => "29 rue de",
            "hiding_place[city]" => "Deaz",
            "hiding_place[postalCode]" => "1424",
            "hiding_place[type]" => "Bunker",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditHidingPlace(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $hidingPlaceRepository = $entityManager->getRepository(HidingPlace::class);
        /** @var Contact $contact */
        $hidinPlace = $hidingPlaceRepository->findOneByCity("Deaz");
        $hidinPlaceId = $hidinPlace->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_hidingPlace", ['idHidingPlace' => $hidinPlaceId])
        );

        $form = $crawler->filter("form[name=hiding_place]")->form([
            "hiding_place[address]" => "1 rue de la ferme",
            "hiding_place[city]" => "Belfort",
            "hiding_place[postalCode]" => "90000",
            "hiding_place[type]" => "Bunker",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
