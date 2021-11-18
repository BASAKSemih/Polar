<?php

namespace App\Tests\HidingPlace;

use App\Entity\Contact;
use App\Entity\HidingPlace;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class HidingPlaceTest extends WebTestCase
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

    public function testCreateCountryForHidingPlace(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_country'));

        $form = $crawler->filter("form[name=country]")->form([
            "country[name]" => "Belgique",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateHidingPlace(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_hidingPlace'));

        $form = $crawler->filter("form[name=hiding_place]")->form([
            "hiding_place[address]" => "29 rue de Paris",
            "hiding_place[city]" => "Paris",
            "hiding_place[postalCode]" => "75126",
            "hiding_place[type]" => "Maison",
            "hiding_place[country]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateHidingPlaceForEdit(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_hidingPlace'));

        $form = $crawler->filter("form[name=hiding_place]")->form([
            "hiding_place[address]" => "29 rue de",
            "hiding_place[city]" => "Deaz",
            "hiding_place[postalCode]" => "1424",
            "hiding_place[type]" => "Bunker",
            "hiding_place[country]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditHidingPlace(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $hidingPlaceRepository = $entityManager->getRepository(HidingPlace::class);
        $hidinPlace = $hidingPlaceRepository->findOneByCity("Deaz");
        $hidinPlaceId = $hidinPlace->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_hidingPlace", ['idHidingPlace' => $hidinPlaceId])
        );

        $form = $crawler->filter("form[name=hiding_place]")->form([
            "hiding_place[address]" => "1 rue de la ferme",
            "hiding_place[city]" => "Belfort",
            "hiding_place[postalCode]" => "90000",
            "hiding_place[type]" => "Bunker",
            "hiding_place[country]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
