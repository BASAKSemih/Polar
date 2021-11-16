<?php

namespace App\Tests\HidingPlace;

use App\Entity\Contact;
use App\Entity\HidingPlace;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class HidingPlaceTest extends WebTestCase
{
    public function testCreateCountryForHidingPlace(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_country'));

        $form = $crawler->filter("form[name=country]")->form([
            "country[name]" => "Belgique",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

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
            "hiding_place[country]" => 1,
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
            "hiding_place[country]" => 1,
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
            "hiding_place[country]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
