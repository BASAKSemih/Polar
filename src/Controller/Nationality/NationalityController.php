<?php

namespace App\Controller\Nationality;

use App\Entity\Nationality;
use App\Form\NationalityType;
use App\Repository\NationalityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NationalityController extends AbstractController
{
    protected NationalityRepository $nationalityRepository;
    protected EntityManagerInterface $entityManager;

    public function __construct(NationalityRepository $nationalityRepository, EntityManagerInterface $entityManager)
    {
        $this->nationalityRepository = $nationalityRepository;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/admin/cree-une-nationnalite", name="create_nationality")
     */
    public function createNationality(Request $request): Response
    {
        $nationality = new Nationality();
        $form = $this->createForm(NationalityType::class, $nationality)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search_exist = $this->nationalityRepository->findByName($nationality->getName());
            if (!$search_exist) {
                $this->entityManager->persist($nationality);
                $this->entityManager->flush();
                $this->addFlash('success', 'La nationnalité a bien été ajouter');
                return $this->redirectToRoute('homePage');
            }
            $this->addFlash('warning', 'Cette nationnalité existe déjà');
            $form = $this->createForm(NationalityType::class, $nationality)->handleRequest($request);
            return $this->render('user/register.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('agent/nationality/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
