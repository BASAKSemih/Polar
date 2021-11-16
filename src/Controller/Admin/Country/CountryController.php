<?php

namespace App\Controller\Admin\Country;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AbstractController
{
    protected CountryRepository $countryRepository;
    protected EntityManagerInterface $entityManager;

    public function __construct(CountryRepository $countryRepository, EntityManagerInterface $entityManager)
    {
        $this->countryRepository = $countryRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/cree-un-pays", name="create_country")
     */
    public function createCountry(Request $request)
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($country);
            $this->entityManager->flush();
            $this->addFlash('success', 'Le pays à bien été crée');
            return $this->redirectToRoute('homePage');
        }
        return $this->render('admin/country/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/modifier-un-pays/{idCountry}", name="edit_country")
     */
    public function editCountry(Request $request, $idCountry)
    {
        $country = $this->countryRepository->findOneById($idCountry);
        if (!$country) {
            $this->addFlash('warning', "Ce pays n'existe pas");
            return $this->redirectToRoute('homePage');
        }
        $form = $this->createForm(CountryType::class, $country)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Le pays à bien été modifier');
            return $this->redirectToRoute('homePage');
        }
        return $this->render('admin/country/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
