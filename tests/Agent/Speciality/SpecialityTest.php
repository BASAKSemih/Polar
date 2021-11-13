<?php

namespace App\Tests\Agent\Speciality;

use App\Entity\Speciality;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class SpecialityTest extends WebTestCase
{
    public function testCreateSpeciality(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_speciality'));

        $form = $crawler->filter("form[name=speciality]")->form([
            "speciality[name]" => "Arme Explosif",
            "speciality[description]" => "Manie très bien les armes explosifs"
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testIfSpecialityExist(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_speciality'));

        $form = $crawler->filter("form[name=speciality]")->form([
            "speciality[name]" => "Arme Explosif",
            "speciality[description]" => "Manie très bien les armes explosifs"
        ]);
        $client->submit($form);
        self::assertRouteSame('create_speciality');
    }

    public function testCreateSpecialityForTest(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_speciality'));

        $form = $crawler->filter("form[name=speciality]")->form([
            "speciality[name]" => "Furti",
            "speciality[description]" => "Se cache très bien"
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditSpecialityError(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $specialityRepository = $entityManager->getRepository(Speciality::class);
        /** @var Speciality $speciality */
        $speciality = $specialityRepository->findOneByName("Furti");
        $speciality_id = $speciality->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_speciality", ['idSpeciality' => $speciality_id])
        );
        $form = $crawler->filter("form[name=speciality]")->form([
            "speciality[name]" => "Arme Explosif",
            "speciality[description]" => "Manie très bien les armes explosifs"
        ]);
        $client->submit($form);
        self::assertRouteSame('edit_speciality');
    }

    public function testEditSpeciality(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $specialityRepository = $entityManager->getRepository(Speciality::class);
        /** @var Speciality $speciality */
        $speciality = $specialityRepository->findOneByName("Furti");
        $speciality_id = $speciality->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_speciality", ['idSpeciality' => $speciality_id])
        );
        $form = $crawler->filter("form[name=speciality]")->form([
            "speciality[name]" => "Furtivité",
            "speciality[description]" => "Se cache très bien"
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
