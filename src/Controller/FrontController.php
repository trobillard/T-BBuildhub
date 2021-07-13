<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
  * @IsGranted("IS_AUTHENTICATED_FULLY")
  */
class FrontController extends AbstractController
{
    #[Route('/front', name: 'front')]
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $projects = $this->getUSer()->getProjects();

        return $this->render('front/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/front/project/new', name: 'newProject')]
    public function newProject(Request $request): Response
    {
        // Creation de l objet vide a hydrater
        $project = new Project();
        // Creation de l objet form sur la base de la classe ProjectType et tu vas hydrater l objet sur la var $project
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $project->setCreateDate(new \DateTime());
            $project->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();
            $this->addFlash(
                "success",
                "your subject was opened, you just need to wait any answers"
            );
            return $this->redirectToRoute('index');
        }

        return $this->render('front/newProject.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/front/project/{id}', name: 'single', requirements: ["id"=>"\d+"])]
    public function single(int $id=1, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->find($id);
        return $this->render('front/single.html.twig', [
            "project" => $project
        ]);
    }
}
