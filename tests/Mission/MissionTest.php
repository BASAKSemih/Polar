<?php

namespace App\Tests\Mission;

use App\Entity\Mission;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class MissionTest extends WebTestCase
{
    public function testCreateTarget(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_target'));

        $form = $crawler->filter("form[name=target]")->form([
            "target[firstName]" => "Aza",
            "target[lastName]" => "qsd",
            "target[birthDate][day]" => "11",
            "target[birthDate][month]" => "11",
            "target[birthDate][year]" => "2016",
            "target[nationality]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateMission(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_mission'));

        $form = $crawler->filter("form[name=mission]")->form([
            "mission[title]" => "Française",
            "mission[description]" => "Française",
            "mission[country]" => "Française",
            "mission[type]" => "Surveillance",
            "mission[status]" => "Terminé",
            "mission[speciality]" => "Française",
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
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateMissionForEdit(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_mission'));

        $form = $crawler->filter("form[name=mission]")->form([
            "mission[title]" => "Edit",
            "mission[description]" => "Edit",
            "mission[country]" => "Edit",
            "mission[type]" => "Surveillance",
            "mission[status]" => "Terminé",
            "mission[speciality]" => "Edit",
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
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditMission(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $missionRepository = $entityManager->getRepository(Mission::class);
        $mission = $missionRepository->findOneByCountry("Edit");
        /** @var Mission $idMission */
        $idMission = $mission->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_mission", ['idMission' => $idMission])
        );

        $form = $crawler->filter("form[name=mission]")->form([
            "mission[title]" => "Edited",
            "mission[description]" => "Edited",
            "mission[country]" => "Edited",
            "mission[type]" => "Surveillance",
            "mission[status]" => "Terminé",
            "mission[speciality]" => "Edit",
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
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
