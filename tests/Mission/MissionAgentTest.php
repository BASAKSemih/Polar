<?php

namespace App\Tests\Mission;

use App\Entity\Agent;
use App\Entity\Contact;
use App\Entity\Country;
use App\Entity\HidingPlace;
use App\Entity\Nationality;
use App\Entity\Speciality;
use App\Entity\Target;
use App\Repository\AdminRepository;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class MissionAgentTest extends WebTestCase
{
    /**
     * @param string $email
     * @return KernelBrowser
     */
    public static function createAuthenticatedClient(string $email): KernelBrowser
    {
        $client = static::createClient();

        $client->getCookieJar()->clear();

        /** @var SessionInterface $session */
        $session = $client->getContainer()->get('session');

        $user = self::$container->get(AdminRepository::class)->findOneByEmail($email);

        $firewallContext = 'main';

        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewallContext, $user->getRoles());
        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

        return $client;
    }

    public function testCreateCountry(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
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
        $client = self::createAuthenticatedClient("email@email.com");
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
        $client = self::createAuthenticatedClient("email@email.com");
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
        $client = self::createAuthenticatedClient("email@email.com");
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

    /**
     * @depends testCreateSpeciality
     */
    public function testCreateAgent(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $nationalityRepository = $entityManager->getRepository(Nationality::class);
        $nationality = $nationalityRepository->findOneByName("Mexicain");
        $nationalityId = $nationality->getId();

        $specialityRepository = $entityManager->getRepository(Speciality::class);
        $speciality = $specialityRepository->findOneByName("Vol");
        $specialityId = $speciality->getId();

        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_agent'));

        $form = $crawler->filter("form[name=agent]")->form([
            "agent[firstName]" => "Doeeeee",
            "agent[lastName]" => "John",
            "agent[birthDate][day]" => "11",
            "agent[birthDate][month]" => "11",
            "agent[birthDate][year]" => "2016",
            "agent[biography]" => "L'agent 007",
            "agent[nationality]" => $nationalityId,
            "agent[speciality]" => $specialityId
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateSpeciality(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_speciality'));

        $form = $crawler->filter("form[name=speciality]")->form([
            "speciality[name]" => "Vol",
            "speciality[description]" => "Vol des objects de valeur"
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateTarget(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
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
        $client = self::createAuthenticatedClient("email@email.com");
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
        $specialityRepository = $entityManager->getRepository(Speciality::class);
        $speciality = $specialityRepository->findOneByName("Vol");
        $specialityId = $speciality->getId();
        $form = $crawler->filter("form[name=mission]")->form([
            "mission[title]" => "Française",
            "mission[description]" => "Française",
            "mission[country]" => $countryId,
            "mission[type]" => "Surveillance",
            "mission[status]" => "Terminé",
            "mission[speciality]" => $specialityId,
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
