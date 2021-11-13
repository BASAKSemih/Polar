<?php

namespace App\Tests\Agent\Nationality;

use App\Entity\Nationality;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class NationalityTest extends WebTestCase
{
    public function testCreateNationality(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_nationality'));

        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Française",
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
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_nationality'));

        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Française",
        ]);
        $client->submit($form);
        self::assertRouteSame('create_nationality');
    }

    public function testCreateNationnalityForEdit(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_nationality'));

        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Russ",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditNationalityError(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $nationalityRepository = $entityManager->getRepository(Nationality::class);
        /** @var Nationality $nationality */
        $nationality = $nationalityRepository->findOneByName("Russ");
        $nationality_id = $nationality->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_nationality", ['idNationality' => $nationality_id])
        );
        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Française",
        ]);
        $client->submit($form);
        self::assertRouteSame('edit_nationality');
    }

    public function testEditNationality(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $nationalityRepository = $entityManager->getRepository(Nationality::class);
        /** @var Nationality $nationality */
        $nationality = $nationalityRepository->findOneByName("Russ");
        $nationality_id = $nationality->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_nationality", ['idNationality' => $nationality_id])
        );
        $form = $crawler->filter("form[name=nationality]")->form([
            "nationality[name]" => "Russe",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
