<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;



#[Route('/task')]
class TaskController extends AbstractController
{
    // #[Route('/', name: 'task_index', methods: ['GET'])]
    // public function index(TaskRepository $taskRepository): Response
    // {   
        
    //    $tasks = $this->getProject()->getTasks();
    //     return $this->render('task/index.html.twig', [
    //         'tasks' => $tasks
    //     ]);
    // }

    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'task_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setPublished(new \DateTime());
            $task->setProject($this->getProject());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    // #[Route('/new', name: 'task_new', methods: ['GET', 'POST'])]
    // public function new(Request $request): Response
    // {
    //     $task = new Task();
    //     $form = $this->createForm(TaskType::class, $task);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $task->setPublished(new \DateTime());
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($task);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('task/new.html.twig', [
    //         'task' => $task,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    }
}
