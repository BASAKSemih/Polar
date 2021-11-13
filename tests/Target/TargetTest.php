<?php

namespace App\Tests\Target;

use App\Entity\Target;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class TargetTest extends WebTestCase
{
    public function testCreateTarget(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_target'));

        $form = $crawler->filter("form[name=target]")->form([
            "target[firstName]" => "James",
            "target[lastName]" => "Bond",
            "target[birthDate][day]" => "11",
            "target[birthDate][month]" => "11",
            "target[birthDate][year]" => "2016",
            "target[nationality]" => 2,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateTargetForEdit(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_target'));

        $form = $crawler->filter("form[name=target]")->form([
            "target[firstName]" => "Jhon",
            "target[lastName]" => "Doaz",
            "target[birthDate][day]" => "4",
            "target[birthDate][month]" => "4",
            "target[birthDate][year]" => "2018",
            "target[nationality]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditTarget(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $targetRepository = $entityManager->getRepository(Target::class);
        $target = $targetRepository->findOneByFirstName("Jhon");
        $targetId = $target->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_target", ['idTarget' => $targetId])
        );

        $form = $crawler->filter("form[name=target]")->form([
            "target[firstName]" => "Azerty",
            "target[lastName]" => "Ytreza",
            "target[birthDate][day]" => "5",
            "target[birthDate][month]" => "5",
            "target[birthDate][year]" => "2019",
            "target[nationality]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');

    }
}