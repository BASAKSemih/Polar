<?php

namespace App\Tests\Mission;

use App\Entity\Country;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class MissionCountryTest extends WebTestCase
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

    public function testCreateTargetForErrorMission(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_target'));

        $form = $crawler->filter("form[name=target]")->form([
            "target[firstName]" => "Luc",
            "target[lastName]" => "Doe",
            "target[birthDate][day]" => "11",
            "target[birthDate][month]" => "11",
            "target[birthDate][year]" => "2016",
            "target[nationality]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateCountry(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_country'));

        $form = $crawler->filter("form[name=country]")->form([
            "country[name]" => "Pologne",
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }



    public function testMissionFailCondition(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_mission'));

        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $countryRepository = $entityManager->getRepository(Country::class);
        $country = $countryRepository->findOneByName("Pologne");
        $countryId = $country->getId();

        $form = $crawler->filter("form[name=mission]")->form([
            "mission[title]" => "Française",
            "mission[description]" => "Française",
            "mission[country]" => $countryId,
            "mission[type]" => "Surveillance",
            "mission[status]" => "Terminé",
            "mission[speciality]" => 1,
            "mission[dateStart][day]" => "11",
            "mission[dateStart][month]" => "11",
            "mission[dateStart][year]" => "2016",
            "mission[dateEnd][day]" => "11",
            "mission[dateEnd][month]" => "11",
            "mission[dateEnd][year]" => "2016",
            "mission[agent]" => 1,
            "mission[contact]" => 1,
            "mission[target]" => 1,
            "mission[hidingPlace]" => 1,
        ]);
        $client->submit($form);
        self::assertRouteSame('create_mission');
    }
}
