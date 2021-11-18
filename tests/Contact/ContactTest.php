<?php

namespace App\Tests\Contact;

use App\Entity\Contact;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ContactTest extends WebTestCase
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

    public function testCreateContact(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_contact'));

        $form = $crawler->filter("form[name=contact]")->form([
            "contact[firstName]" => "Aza",
            "contact[lastName]" => "Bab",
            "contact[birthDate][day]" => "2",
            "contact[birthDate][month]" => "2",
            "contact[birthDate][year]" => "2017",
            "contact[nationality]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateContactForEdit(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate('create_contact'));

        $form = $crawler->filter("form[name=contact]")->form([
            "contact[firstName]" => "Bob",
            "contact[lastName]" => "Pop",
            "contact[birthDate][day]" => "2",
            "contact[birthDate][month]" => "2",
            "contact[birthDate][year]" => "2017",
            "contact[nationality]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    /**
     * @depends testCreateContactForEdit
     */
    public function testEditContact(): void
    {
        $client = self::createAuthenticatedClient("email@email.com");
        $router = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        $contactRepository = $entityManager->getRepository(Contact::class);
        /** @var Contact $contact */
        $contact = $contactRepository->findOneByFirstName("Bob");
        $contactId = $contact->getId();
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate("edit_contact", ['idContact' => $contactId])
        );

        $form = $crawler->filter("form[name=contact]")->form([
            "contact[firstName]" => "Pop",
            "contact[lastName]" => "Pop",
            "contact[birthDate][day]" => "2",
            "contact[birthDate][month]" => "2",
            "contact[birthDate][year]" => "2017",
            "contact[nationality]" => 1,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
