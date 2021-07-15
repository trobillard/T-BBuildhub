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
            // $task->setProject($project);
            // $task->setProject($this->getProject($project));

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
//   ANCIEN CODE DU CRUD IN CASE !!!!!!!!!!!!!!!!!!
    // #[Route('/', name: 'task_index', methods: ['GET'])]
    // public function index(TaskRepository $taskRepository): Response
    // {
    //     return $this->render('task/index.html.twig', [
    //         'tasks' => $taskRepository->findAll(),
    //     ]);
    // }


    // #[Route('/new/{id}', name: 'task_new', methods: ['GET', 'POST'], requirements: ["id"=>"\d+"])]
    // // "\d+" regex pour dire id d+
    // public function single(int $id=1, ProjectRepository $projectRepository, Request $request): Response
    // // $id=1 est fait pour avoir un parametre par defaut et afficher le post id=1 
    // // SubjectRepository $subjectRepository (injection de service) possible seulement car on a charger la classe dans le controller
    // {
    //     $project = $projectRepository->find($id);
    //     // getSingleSubject methode fait dans le SubjectRepository permet de remplacer la methode find
    //     // creation de l objet vide (objet a hydrater)
    //     $task = new Task();
    //     // creation de l objet formulaire sur la base de la classe SubjectType et tu vas hydrater l objet sur la var $subject
    //     $form = $this->createForm(TaskType::class, $task);
    //     // dire au formulaire de traiter la requete (representer par $request) injecter dans new Subject parametre $request
    //     // find est une methode du repository qui va chercher par defaut la clef primaire
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $task->setPublished(new \DateTime()); 
    //         // instancier la date et l heure du jour new \DateTime permet d implementer DateTimeInterface donc de ne pas rentrer la valeur dans le form et de l'obtenir
    //         // le \ permet de dire d'aller chercher DateTime a la racine 
    //         $task->setProject($project);
    //         // On associe au sujet l utilisateur connecte qu on recupere via le controller
    //         $entityManager = $this->getDoctrine()->getManager();
    //         // je vais chercher l entityManager , le manager de doctrine que j enregistre dans une variable
    //         $entityManager->persist($task);
    //         // persist prepare l enregistrement en bdd
    //         $entityManager->flush();
    //         // le flush permet l enregistrement en bdd / le flush fait un commit pour valider
    //         return $this->redirectToRoute('index');
    //     }
    //     return $this->render('task/new.html.twig', [
    //         "task" => $task,
    //         "project" => $project,
    //         "form" => $form->createView()
    //         ]);
    // }
    // #[Route('/new/{id}', name: 'task_new', methods: ['GET', 'POST'], requirements: ["id"=>"\d+"])]
   
    // public function single(int $id=1, ProjectRepository $projectRepository, Request $request): Response
    
    // {
    //     $project = $projectRepository->find($id);
    //     $task = new Task();
    //     $form = $this->createForm(TaskType::class, $task);
       
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $task->setPublished(new \DateTime()); 
    //         $task->setProject($project);

    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($task);
    //         $entityManager->flush();
    //         return $this->redirectToRoute('index');
    //     }
    //     return $this->render('task/new.html.twig', [
    //         "task" => $task,
    //         "form" => $form->createView()
    //         ]);
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
