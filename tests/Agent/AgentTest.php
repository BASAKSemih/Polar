<?php

namespace App\Tests\Agent;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class AgentTest extends WebTestCase
{
    /**
     * @depends testCreateNationnality, testCreateSpeciality
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

    public function testCreateNationnality(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_nationality'));

        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Américain",
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

}