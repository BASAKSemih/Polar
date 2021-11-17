<?php

namespace App\Tests\Mission;

use App\Entity\Agent;
use App\Entity\Contact;
use App\Entity\Country;
use App\Entity\HidingPlace;
use App\Entity\Nationality;
use App\Entity\Target;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class MissionAgentTest extends WebTestCase
{
    public function testCreateCountry(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_country'));

        $form = $crawler->filter("form[name=country]")->form([
            "country[name]" => "Mexique",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    /**
     * @depends testCreateCountry
     */
    public function testCreateHidingPlace(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        /** @var Country $country */
        $country = $countryRepository->findOneByName("Mexique");
        $countryId = $country->getId();
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_hidingPlace'));

        $form = $crawler->filter("form[name=hiding_place]")->form([
            "hiding_place[address]" => "29 rue de Paris",
            "hiding_place[city]" => "Mulhouseeee",
            "hiding_place[postalCode]" => "75126",
            "hiding_place[type]" => "Maison",
            "hiding_place[country]" => $countryId,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    /**
     * @depends testCreateCountry
     */
    public function testCreateNationality(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_nationality'));

        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        /** @var Country $country */
        $country = $countryRepository->findOneByName("Mexique");
        $countryId = $country->getId();


        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Mexicain",
            "nationality[country]" => $countryId,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    /**
     * @depends testCreateNationality
     */
    public function testCreateContact(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $nationalityRepository = $entityManager->getRepository(Nationality::class);
        $nationality = $nationalityRepository->findOneByName("Mexicain");
        $nationalityId = $nationality->getId();
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_contact'));

        $form = $crawler->filter("form[name=contact]")->form([
            "contact[firstName]" => "qsdqsdqsdqsdsqdqsdqsdqsdqsd",
            "contact[lastName]" => "qsdqsdqsdsqdqsdqsdqsd",
            "contact[birthDate][day]" => "2",
            "contact[birthDate][month]" => "2",
            "contact[birthDate][year]" => "2017",
            "contact[nationality]" => $nationalityId,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateAgent(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $nationalityRepository = $entityManager->getRepository(Nationality::class);
        $nationality = $nationalityRepository->findOneByName("Mexicain");
        $nationalityId = $nationality->getId();

        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_agent'));

        $form = $crawler->filter("form[name=agent]")->form([
            "agent[firstName]" => "Doeeeee",
            "agent[lastName]" => "John",
            "agent[birthDate][day]" => "11",
            "agent[birthDate][month]" => "11",
            "agent[birthDate][year]" => "2016",
            "agent[biography]" => "L'agent 007",
            "agent[nationality]" => $nationalityId,
            "agent[speciality]" => 1
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateTarget(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $nationalityRepository = $entityManager->getRepository(Nationality::class);
        $nationality = $nationalityRepository->findOneByName("Mexicain");
        $nationalityId = $nationality->getId();
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_target'));
        $form = $crawler->filter("form[name=target]")->form([
            "target[firstName]" => "Semihh",
            "target[lastName]" => "Bond",
            "target[birthDate][day]" => "11",
            "target[birthDate][month]" => "11",
            "target[birthDate][year]" => "2016",
            "target[nationality]" => $nationalityId,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    /**
     * @depends testCreateContact
     */
    public function testCreateMissionWithSameNationalityAgentTarget(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_mission'));
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        $country = $countryRepository->findOneByName("Mexique");
        $countryId = $country->getId();
        $agentRepository = $entityManager->getRepository(Agent::class);
        $agent = $agentRepository->findOneByfirstName("Doeeeee");
        $agentId = $agent->getId();
        $contactRepository = $entityManager->getRepository(Contact::class);
        $contact = $contactRepository->findOneByfirstName("qsdqsdqsdqsdsqdqsdqsdqsdqsd");
        $contactId = $contact->getId();
        $targetRepository = $entityManager->getRepository(Target::class);
        $target = $targetRepository->findOneByfirstName("Semihh");
        $targetId = $target->getId();
        $hidingPlaceRepository = $entityManager->getRepository(HidingPlace::class);
        $hidingPlace = $hidingPlaceRepository->findOneByCity("Mulhouseeee");
        $hidingPlaceId = $hidingPlace->getId();
        $form = $crawler->filter("form[name=mission]")->form([
            "mission[title]" => "Française",
            "mission[description]" => "Française",
            "mission[country]" => $countryId,
            "mission[type]" => "Surveillance",
            "mission[status]" => "Terminé",
            "mission[speciality]" => "Française",
            "mission[dateStart][day]" => "11",
            "mission[dateStart][month]" => "11",
            "mission[dateStart][year]" => "2016",
            "mission[dateEnd][day]" => "11",
            "mission[dateEnd][month]" => "11",
            "mission[dateEnd][year]" => "2016",
            "mission[agent]" => $agentId,
            "mission[contact]" => $contactId,
            "mission[target]" => $targetId,
            "mission[hidingPlace]" => $hidingPlaceId,
        ]);
        $client->submit($form);
        self::assertRouteSame('create_mission');
    }
}
