<?php

namespace App\Tests\Agent;

use App\Entity\Agent;
use App\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class AgentTest extends WebTestCase
{
    /**
     * @depends testCreateNationality, testCreateSpeciality
     */
    public function testCreateAgent(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_agent'));

        $form = $crawler->filter("form[name=agent]")->form([
            "agent[firstName]" => "James",
            "agent[lastName]" => "Bond",
            "agent[birthDate][day]" => "11",
            "agent[birthDate][month]" => "11",
            "agent[birthDate][year]" => "2016",
            "agent[biography]" => "L'agent 007",
            "agent[nationality]" => 1,
            "agent[speciality]" => 1
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
            "country[name]" => "Etat-Unis Amerique",
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
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        /** @var Country $country */
        $country = $countryRepository->findOneByName("Etat-Unis Amerique");
        $countryId = $country->getId();

        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_nationality'));

        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Américain",
            "nationality[country]" => $countryId
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateSpeciality(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_speciality'));

        $form = $crawler->filter("form[name=speciality]")->form([
            "speciality[name]" => "Combat Rapprochée",
            "speciality[description]" => "Très bon combatant a mains nue"
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateAgentForEdit(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_agent'));

        $form = $crawler->filter("form[name=agent]")->form([
            "agent[firstName]" => "Polar",
            "agent[lastName]" => "Bond",
            "agent[birthDate][day]" => "11",
            "agent[birthDate][month]" => "11",
            "agent[birthDate][year]" => "2016",
            "agent[biography]" => "Polar Grimmes",
            "agent[nationality]" => 1,
            "agent[speciality]" => 1
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditAgent(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $agentRepository = $entityManager->getRepository(Agent::class);
        /** @var Agent $agent */
        $agent = $agentRepository->findOneByfirstName("Polar");
        $agentId = $agent->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_agent", ['idAgent' => $agentId])
        );
        $form = $crawler->filter("form[name=agent]")->form([
            "agent[firstName]" => "John",
            "agent[lastName]" => "Wick",
            "agent[birthDate][day]" => "11",
            "agent[birthDate][month]" => "11",
            "agent[birthDate][year]" => "2016",
            "agent[biography]" => "Le croc mitel",
            "agent[nationality]" => 1,
            "agent[speciality]" => 1
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
