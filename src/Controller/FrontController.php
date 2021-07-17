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
// TO SHOW ALL THE PROJECT BY ID AND CREATE NEW ONE
    #[Route('/front', name: 'front')]
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $projects = $this->getUSer()->getProjects();
        $project = new Project();
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

        return $this->render('front/index.html.twig', [
            "form" => $form->createView(),
            'projects' => $projects,
        ]);
    }

    //TO SHOW ONE SINGLE PROJECT AND CAN EDIT IT ASWELL 
    #[Route('/front/project/{id}', name: 'single', requirements: ["id"=>"\d+"])]
    public function edit(int $id, Request $request, ProjectRepository $projectRepository): Response
    {   
        $project = $projectRepository->find($id);
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/single.html.twig', [
            "project" => $project,
            'form' => $form,
        ]);
    }

    
    // TO DELETE A PROJECT
    #[Route('front/{id}', name: 'delete', methods: ['POST'])]
    public function delete(int $id,Request $request, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }
}

// ///////////////////////////////OPTION/////////////////////////////////////////
// TO SHOW ALL THE PROJECT BY ID UF NO USE OF MODAL

// #[Route('/front', name: 'front')]
    // #[Route('/', name: 'index')]
    // public function index(): Response
    // {
    //     $projectRepository = $this->getDoctrine()->getRepository(Project::class);
    //     $projects = $this->getUSer()->getProjects();

    //     return $this->render('front/index.html.twig', [
    //         'projects' => $projects,
    //     ]);
    // }
///////////////////////////////////////////////////////////////////////////////////
    // TO CREATE A NEW PROJECT

    // #[Route('/front/project/new', name: 'newProject')]
    // public function newProject(Request $request): Response
    // {
    //     // Creation de l objet vide a hydrater
    //     $project = new Project();
    //     // Creation de l objet form sur la base de la classe ProjectType et tu vas hydrater l objet sur la var $project
    //     $form = $this->createForm(ProjectType::class, $project);
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted()&& $form->isValid()) {
    //         $project->setCreateDate(new \DateTime());
    //         $project->setUser($this->getUser());
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($project);
    //         $entityManager->flush();
    //         $this->addFlash(
    //             "success",
    //             "your subject was opened, you just need to wait any answers"
    //         );
    //         return $this->redirectToRoute('index');
    //     }

    //     return $this->render('front/newProject.html.twig', [
    //         "form" => $form->createView()
    //     ]);
    // }
/////////////////////////////////////////////////////////////////////////////////

// TO SHOW ONLY ONE PROJECT IF NOT USE OF A MODAL

// #[Route('/front/project/{id}', name: 'single', requirements: ["id"=>"\d+"])]
    // public function single(int $id, ProjectRepository $projectRepository): Response
    // {
    //     $project = $projectRepository->find($id);
    //     return $this->render('front/single.html.twig', [
    //         "project" => $project
    //     ]);
    // }
/////////////////////////////////////////////////////////////////////////////////

// TO EDIT THE PROJECT IN A DIFFERENT PAGE NOT WITH A MODAL

    // #[Route('/front/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    // public function edit(int $id, Request $request, ProjectRepository $projectRepository): Response
    // {   
    //     $project = $projectRepository->find($id);
    //     $form = $this->createForm(ProjectType::class, $project);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('front/edit.html.twig', [
    //         "project" => $project,
    //         'form' => $form,
    //     ]);
    // }
/////////////////////////////////////////////////////////////////////////////////