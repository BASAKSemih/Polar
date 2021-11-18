<?php

namespace App\Tests\Agent\Speciality;

use App\Entity\Speciality;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SpecialityTest extends WebTestCase
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

    public function testCreateSpeciality(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
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
        $client = self::createAuthenticatedClient("email@email.com");
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
        $client = self::createAuthenticatedClient("email@email.com");
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
        $client = self::createAuthenticatedClient("email@email.com");
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
        $client = self::createAuthenticatedClient("email@email.com");
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
