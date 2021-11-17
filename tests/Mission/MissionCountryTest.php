<?php

namespace App\Tests\Mission;

use App\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class MissionCountryTest extends WebTestCase
{
    public function testCreateTargetForErrorMission(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_target'));

        $form = $crawler->filter("form[name=target]")->form([
            "target[firstName]" => "Luc",
            "target[lastName]" => "Doe",
            "target[birthDate][day]" => "11",
            "target[birthDate][month]" => "11",
            "target[birthDate][year]" => "2016",
            "target[nationality]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateCountry(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_country'));

        $form = $crawler->filter("form[name=country]")->form([
            "country[name]" => "Pologne",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }



    public function testMissionFailCondition(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_mission'));

        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        $country = $countryRepository->findOneByName("Pologne");
        $countryId = $country->getId();

        $form = $crawler->filter("form[name=mission]")->form([
            "mission[title]" => "Française",
            "mission[description]" => "Française",
            "mission[country]" => $countryId,
            "mission[type]" => "Surveillance",
            "mission[status]" => "Terminé",
            "mission[speciality]" => 1,
            "mission[dateStart][day]" => "11",
            "mission[dateStart][month]" => "11",
            "mission[dateStart][year]" => "2016",
            "mission[dateEnd][day]" => "11",
            "mission[dateEnd][month]" => "11",
            "mission[dateEnd][year]" => "2016",
            "mission[agent]" => 1,
            "mission[contact]" => 1,
            "mission[target]" => 1,
            "mission[hidingPlace]" => 1,
        ]);
        $client->submit($form);
        self::assertRouteSame('create_mission');
    }
}
