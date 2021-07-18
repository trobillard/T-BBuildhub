<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;



#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/{id}', name: 'task_index', methods: ['GET', 'POST'], requirements: ['id'=>'\d+'])]
    public function index(ProjectRepository $projectRepository, Request $request, TaskRepository $taskRepository, int $id): Response
    {   
        $project=$projectRepository->find($id);
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
  
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setPublished(new \DateTime()); 
            $project=$projectRepository->find($id);
            $task->setProject($project);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('task_index', ["id"=>$task->getProject()->getId()]);
            
        }
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->find($id),
            "task" => $task,
            "form" => $form->createView(),
            "project" => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_index', ["id"=>$task->getProject()->getId()]);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

   
///////////////////////////////////////////////////////////////////////////////////   
    // #[Route('/{id}', name: 'task_delete', methods: ['POST'])]
    // public function delete(ProjectRepository $projectRepository, Request $request, Task $task, int $id): Response
    // {   
    //     $project=$projectRepository->find($id);
    //     $task->setProject($project);
    //     $task = $taskRepository->find($id);
    //     if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($task);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('index', ["id"=>$task->getProject()->getId(),"project" => $project, ]);
    // }

    // #[Route('front/{id}', name: 'delete', methods: ['POST'])]
    // public function delete(int $id,Request $request, ProjectRepository $projectRepository): Response
    // {
    //     $project = $projectRepository->find($id);
    //     if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($project);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    // }

 // #[Route('/{id}', name: 'task_show', methods: ['GET', 'POST'])]
    // public function show(ProjectRepository $projectRepository, Task $task, int $id): Response
    // {
    //     $project=$projectRepository->find($id);
    //     $task->setProject($project);
    //     $task = $taskRepository->find($id);

    //     return $this->render('task/show.html.twig', [
    //         'task' => $task,
    //         'tasks' => $taskRepository->find($id),
    //         "project" => $project,
    //     ]);
    // }

    // #[Route('/front/project/{id}', name: 'single', requirements: ["id"=>"\d+"])]
    // public function single(int $id=1, ProjectRepository $projectRepository): Response
    // {
    //     $project = $projectRepository->find($id);
    //     return $this->render('front/single.html.twig', [
    //         "project" => $project
    //     ]);
    // }
}
