<?php

namespace App\Tests\Agent\Speciality;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class SpecialityTest extends WebTestCase
{
    public function testCreateSpeciality(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_speciality'));

        $form = $crawler->filter("form[name=speciality]")->form([
            "speciality[name]" => "Arme Explosif",
            "speciality[description]" => "Manie très bien les armes explosifs"
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testIfSpecialityExist(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_speciality'));

        $form = $crawler->filter("form[name=speciality]")->form([
            "speciality[name]" => "Arme Explosif",
            "speciality[description]" => "Manie très bien les armes explosifs"
        ]);
        $client->submit($form);
        self::assertRouteSame('create_speciality');
    }
}
