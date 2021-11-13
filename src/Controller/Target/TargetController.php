<?php

namespace App\Controller\Target;

use App\Entity\Target;
use App\Form\TargetType;
use App\Repository\TargetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TargetController extends AbstractController
{
    protected EntityManagerInterface $entityManager;
    protected TargetRepository $targetRepository;

    public function __construct(EntityManagerInterface $entityManager, TargetRepository $targetRepository)
    {
        $this->entityManager = $entityManager;
        $this->targetRepository = $targetRepository;
    }

    /**
     * @Route("/admin/cree-une-cible", name="create_target")
     */
    public function createTarget(Request $request): Response
    {
        $target = new Target();
        $form = $this->createForm(TargetType::class, $target)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($target);
            $this->entityManager->flush();
            $this->addFlash('success', "La cible à bien été crée");
            return $this->redirectToRoute('homePage');
        }
        return $this->render('target/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/modifier-une-cible/{idTarget}", name="edit_target")
     */
    public function editTarget(Request $request, $idTarget): Response
    {
        $target = $this->targetRepository->findOneById($idTarget);
        if (!$target){
            $this->addFlash('warning', "Cette cible n'existe pas");
            return $this->redirectToRoute('homePage');
        }
        $form = $this->createForm(TargetType::class, $target)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            $this->addFlash('success', 'La cible a bien été modifier');
            return $this->redirectToRoute('homePage');
        }
        return $this->render('target/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

}