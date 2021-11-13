<?php

namespace App\Tests\Contact;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ContactTest extends WebTestCase
{
    public function testCreateContact(): void
    {
        $client = static::createClient();
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
        $client = static::createClient();
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
        $client = static::createClient();
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
