<?php

namespace App\Tests\Country;

use App\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class CountryTest extends WebTestCase
{
    public function testCreateCountry(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_country'));

        $form = $crawler->filter("form[name=country]")->form([
            "country[name]" => "France",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateCountryForEdit(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_country'));

        $form = $crawler->filter("form[name=country]")->form([
            "country[name]" => "Brési",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditCountry(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        /** @var Country $country */
        $country = $countryRepository->findOneByName("Brési");
        $countryId = $country->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_country", ['idCountry' => $countryId])
        );
        $form = $crawler->filter("form[name=country]")->form([
            "country[name]" => "Brésil",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
