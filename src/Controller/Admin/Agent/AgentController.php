<?php

namespace App\Controller\Admin\Agent;

use App\Entity\Agent;
use App\Form\AgentType;
use App\Repository\AgentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgentController extends AbstractController
{
    protected EntityManagerInterface $entityManager;
    protected AgentRepository $agentRepository;

    public function __construct(EntityManagerInterface $entityManager, AgentRepository $agentRepository)
    {
        $this->entityManager = $entityManager;
        $this->agentRepository = $agentRepository;
    }

    /**
     * @Route("/admin/cree-un-agent", name="create_agent")
     */
    public function createAgent(Request $request): Response
    {
        $agent = new Agent();
        $form = $this->createForm(AgentType::class, $agent)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($agent);
            $this->entityManager->flush();
            $this->addFlash('success', "L'agent à bien été crée");
            return $this->redirectToRoute('homePage');
        }
        return $this->render('agent/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/modifier-un-agent/{idAgent}", name="edit_agent")
     */
    public function editAgent(Request $request, $idAgent): Response
    {
        $agent = $this->agentRepository->findOneById($idAgent);
        if (!$agent) {
            $this->addFlash('warning', "Cette cible n'existe pas");
            return $this->redirectToRoute('homePage');
        }
        $form = $this->createForm(AgentType::class, $agent)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', "L'agent à bien été modifier");
            return $this->redirectToRoute('homePage');
        }
        return $this->render('agent/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
