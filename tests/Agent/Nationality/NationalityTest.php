<?php

namespace App\Tests\Agent\Nationality;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class NationalityTest extends WebTestCase
{
    public function testCreateNationnality(): void
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
}
