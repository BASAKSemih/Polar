<?php

namespace App\DataFixtures;

use App\Entity\Agent;
use App\Entity\Country;
use App\Entity\HidingPlace;
use App\Entity\Nationality;
use App\Entity\Speciality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $country = new Country();
        $country
            ->setName("Allemagne");
        $manager->persist($country);
        $manager->flush();


        $nationality = new Nationality();
        $nationality
            ->setCountry($country)
            ->setName("Allemand");
        $manager->persist($nationality);
        $manager->flush();

        $country = new Country();
        $country
            ->setName("Grèce");
        $manager->persist($country);
        $manager->flush();

        $nationality = new Nationality();
        $nationality
            ->setCountry($country)
            ->setName("Grec");
        $manager->persist($nationality);
        $manager->flush();

        $country = new Country();
        $country
            ->setName("Brésil");
        $manager->persist($country);
        $manager->flush();

        $nationality = new Nationality();
        $nationality
            ->setCountry($country)
            ->setName("Brésilien");
        $manager->persist($nationality);
        $manager->flush();

        $speciality = new Speciality();
        $speciality
            ->setName("Arme")
            ->setDescription("Manie très bien les armes");
        $manager->persist($speciality);
        $manager->flush();

        $speciality = new Speciality();
        $speciality
            ->setName("Furtif")
            ->setDescription("Se cache très bien");
        $manager->persist($speciality);
        $manager->flush();

        $speciality = new Speciality();
        $speciality
            ->setName("Corp a corp")
            ->setDescription("Et un très bon combatant");
        $manager->persist($speciality);
        $manager->flush();

        $country = new Country();
        $country
            ->setName("France");
        $manager->persist($country);
        $manager->flush();

        $hidePlace = new HidingPlace();
        $hidePlace
            ->setCity("Paris")
            ->setType("Batiment")
            ->setPostalCode("2500")
            ->setCountry($country)
            ->setAddress("Paris Champs");
        $manager->persist($hidePlace);
        $manager->flush();

        $country = new Country();
        $country
            ->setName("USA");
        $manager->persist($country);
        $manager->flush();

        $hidePlace = new HidingPlace();
        $hidePlace
            ->setCity("Floride")
            ->setType("Villa")
            ->setCountry($country)
            ->setPostalCode("2500")
            ->setAddress("Villa Champs");
        $manager->persist($hidePlace);
        $manager->flush();
    }
}
