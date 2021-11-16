<?php

namespace App\Tests\Agent\Nationality;

use App\Entity\Country;
use App\Entity\Nationality;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class NationalityTest extends WebTestCase
{
    public function testCreateCountry(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_country'));

        $form = $crawler->filter("form[name=country]")->form([
            "country[name]" => "Italie",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }


    public function testCreateNationality(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_nationality'));

        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        /** @var Country $country */
        $country = $countryRepository->findOneByName("Italie");
        $countryId = $country->getId();


        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Italiens",
            "nationality[country]" => $countryId,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testIfNationalityExist(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        /** @var Country $country */
        $country = $countryRepository->findOneByName("Italie");
        $countryId = $country->getId();
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_nationality'));

        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Italiens",
            "nationality[country]" => $countryId,
        ]);
        $client->submit($form);
        self::assertRouteSame('create_nationality');
    }

    public function testCreateCountryForEditNationnality(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_country'));

        $form = $crawler->filter("form[name=country]")->form([
            "country[name]" => "Europe",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateNationnalityForEdit(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        /** @var Country $country */
        $country = $countryRepository->findOneByName("Europe");
        $countryId = $country->getId();
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_nationality'));

        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "European",
            "nationality[country]" => $countryId,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }


    public function testEditNationality(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");

        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        /** @var Country $country */
        $country = $countryRepository->findOneByName("Europe");
        $countryId = $country->getId();

        $nationalityRepository = $entityManager->getRepository(Nationality::class);
        /** @var Nationality $nationality */
        $nationality = $nationalityRepository->findOneByName("European");
        $nationality_id = $nationality->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_nationality", ['idNationality' => $nationality_id])
        );
        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Russe",
            "nationality[country]" => $countryId,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
