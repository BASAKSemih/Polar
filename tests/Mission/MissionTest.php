<?php

namespace App\Tests\Mission;

use App\Entity\Country;
use App\Entity\HidingPlace;
use App\Entity\Mission;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class MissionTest extends WebTestCase
{
    public function testCreateCountryForMission(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_country'));

        $form = $crawler->filter("form[name=country]")->form([
            "country[name]" => "Finlande",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateNationnalityForMission(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        /** @var Country $country */
        $country = $countryRepository->findOneByName("Finlande");
        $countryId = $country->getId();
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_nationality'));

        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Finlandaise",
            "nationality[country]" => $countryId,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

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

    public function testCreateHidingPlace(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        /** @var Country $country */
        $country = $countryRepository->findOneByName("Finlande");
        $countryId = $country->getId();
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_hidingPlace'));

        $form = $crawler->filter("form[name=hiding_place]")->form([
            "hiding_place[address]" => "29 rue de Paris",
            "hiding_place[city]" => "Paris",
            "hiding_place[postalCode]" => "75126",
            "hiding_place[type]" => "Maison",
            "hiding_place[country]" => $countryId,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

//    public function testCreateMission(): void
//    {
//        $client = static::createClient();
//        /** @var RouterInterface $router */
//        $router = $client->getContainer()->get("router");
//        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_mission'));
//
//        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
//        $countryRepository = $entityManager->getRepository(Country::class);
//        /** @var Country $country */
//        $country = $countryRepository->findOneByName("Finlande");
//        $countryId = $country->getId();
//
//        $hidePlaceRepository = $entityManager->getRepository(HidingPlace::class);
//        /** @var HidingPlace $hidePlace */
//        $hidePlace = $hidePlaceRepository->findOneByAddress("29 rue de Paris");
//        $hidePlaceId = $hidePlace->getId();
//
//        $form = $crawler->filter("form[name=mission]")->form([
//            "mission[title]" => "Française",
//            "mission[description]" => "Française",
//            "mission[country]" => $countryId,
//            "mission[type]" => "Surveillance",
//            "mission[status]" => "Terminé",
//            "mission[speciality]" => "Française",
//            "mission[dateStart][day]" => "11",
//            "mission[dateStart][month]" => "11",
//            "mission[dateStart][year]" => "2016",
//            "mission[dateEnd][day]" => "11",
//            "mission[dateEnd][month]" => "11",
//            "mission[dateEnd][year]" => "2016",
//            "mission[agent]" => 1,
//            "mission[contact]" => 1,
//            "mission[target]" => 1,
//            "mission[hidingPlace]" => $hidePlaceId,
//        ]);
//        $client->submit($form);
//        $client->followRedirect();
//        self::assertRouteSame('homePage');
//    }
////
////    public function testCreateMissionForEdit(): void
////    {
////        $client = static::createClient();
////        /** @var RouterInterface $router */
////        $router = $client->getContainer()->get("router");
////        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_mission'));
////
////        $form = $crawler->filter("form[name=mission]")->form([
////            "mission[title]" => "Editthis",
////            "mission[description]" => "Edit",
////            "mission[country]" => 1,
////            "mission[type]" => "Surveillance",
////            "mission[status]" => "Terminé",
////            "mission[speciality]" => "Edit",
////            "mission[dateStart][day]" => "11",
////            "mission[dateStart][month]" => "11",
////            "mission[dateStart][year]" => "2016",
////            "mission[dateEnd][day]" => "11",
////            "mission[dateEnd][month]" => "11",
////            "mission[dateEnd][year]" => "2016",
////            "mission[agent]" => 1,
////            "mission[contact]" => 1,
////            "mission[target]" => 1,
////            "mission[hidingPlace]" => 1,
////        ]);
////        $client->submit($form);
////        $client->followRedirect();
////        self::assertRouteSame('homePage');
////    }
////
////    public function testEditMission(): void
////    {
////        $client = static::createClient();
////        $router = $client->getContainer()->get("router");
////        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
////        $missionRepository = $entityManager->getRepository(Mission::class);
////        $mission = $missionRepository->findOneByTitle("Editthis");
////        /** @var Mission $idMission */
////        $idMission = $mission->getId();
////        $crawler = $client->request(
////            Request::METHOD_GET,
////            $router->generate("edit_mission", ['idMission' => $idMission])
////        );
////
////        $form = $crawler->filter("form[name=mission]")->form([
////            "mission[title]" => "Edited",
////            "mission[description]" => "Edited",
////            "mission[country]" => 1,
////            "mission[type]" => "Surveillance",
////            "mission[status]" => "Terminé",
////            "mission[speciality]" => "Edit",
////            "mission[dateStart][day]" => "11",
////            "mission[dateStart][month]" => "11",
////            "mission[dateStart][year]" => "2016",
////            "mission[dateEnd][day]" => "11",
////            "mission[dateEnd][month]" => "11",
////            "mission[dateEnd][year]" => "2016",
////            "mission[agent]" => 1,
////            "mission[contact]" => 1,
////            "mission[target]" => 1,
////            "mission[hidingPlace]" => 1,
////        ]);
////        $client->submit($form);
////        $client->followRedirect();
////        self::assertRouteSame('homePage');
////    }
}
