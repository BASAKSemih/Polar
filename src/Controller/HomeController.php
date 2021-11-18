<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\{AgentRepository, ContactRepository, HidingPlaceRepository, MissionRepository,
    NationalityRepository, SpecialityRepository, TargetRepository, CountryRepository};
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    protected AgentRepository $agentRepository;
    protected ContactRepository $contactRepository;
    protected HidingPlaceRepository $hidingPlaceRepository;
    protected MissionRepository $missionRepository;
    protected NationalityRepository $nationalityRepository;
    protected SpecialityRepository $specialityRepository;
    protected TargetRepository $targetRepository;
    protected CountryRepository $countryRepository;

    public function __construct(
        CountryRepository $countryRepository,
        AgentRepository $agentRepository,
        ContactRepository $contactRepository,
        HidingPlaceRepository $hidingPlaceRepository,
        MissionRepository $missionRepository,
        NationalityRepository $nationalityRepository,
        SpecialityRepository $specialityRepository,
        TargetRepository $targetRepository
    ) {
        $this->countryRepository = $countryRepository;
        $this->agentRepository = $agentRepository;
        $this->contactRepository = $contactRepository;
        $this->hidingPlaceRepository = $hidingPlaceRepository;
        $this->missionRepository = $missionRepository;
        $this->nationalityRepository = $nationalityRepository;
        $this->specialityRepository = $specialityRepository;
        $this->targetRepository = $targetRepository;
    }

    /**
     * @Route("/", name="homePage")
     */
    public function homePage(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/liste-des-cibles", name="list_target")
     */
    public function listTarget(): Response
    {
        $targets = $this->targetRepository->findAll();
        return $this->render('home/target.html.twig', [
            'targets' => $targets
        ]);
    }

    /**
     * @Route("/liste-des-agents", name="list_agent")
     */
    public function listAgent(): Response
    {
        $agents = $this->agentRepository->findAll();
        return $this->render('home/agent.html.twig', [
            'agents' => $agents
        ]);
    }

    /**
     * @Route("/liste-des-contacts", name="list_contact")
     */
    public function listContact(): Response
    {
        $contacts = $this->contactRepository->findAll();
        return $this->render('home/contact.html.twig', [
            'contacts' => $contacts
        ]);
    }

    /**
     * @Route("/liste-des-specialite", name="list_speciality")
     */
    public function listSpeciality(): Response
    {
        $specialitys = $this->specialityRepository->findAll();
        return $this->render('home/speciality.html.twig', [
            'specialitys' => $specialitys
        ]);
    }


    /**
     * @Route("/liste-des-missions", name="list_missions")
     */
    public function listMission(): Response
    {
        $missions = $this->missionRepository->findAll();
        return $this->render('home/mission.html.twig', [
            'missions' => $missions
        ]);
    }

    /**
     * @Route("/liste-des-pays", name="list_country")
     */
    public function listCountry(): Response
    {
        $countrys = $this->countryRepository->findAll();
        return $this->render('home/country.html.twig', [
            'countrys' => $countrys
        ]);
    }

    /**
     * @Route("/liste-des-nationnalite", name="list-nationality")
     */
    public function listNationality(): Response
    {
        $nationalitys = $this->nationalityRepository->findAll();
        return $this->render('home/nationality.html.twig', [
            'nationalitys' => $nationalitys
        ]);
    }

    /**
     * @Route("/liste-des-planques", name="list-hidingPlace")
     */
    public function listHidingPlace(): Response
    {
        $hidingPlace = $this->hidingPlaceRepository->findAll();
        return $this->render('home/hidingPlace.html.twig', [
            'hidingPlace' => $hidingPlace
        ]);
    }
}
