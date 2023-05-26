<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Tasks;
use App\Form\CommentsType;
use App\Form\TasksType;
use App\Repository\CommentsRepository;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasks')]
class TasksController extends AbstractController
{
    #[Route('/', name: 'app_tasks_index', methods: ['GET'])]
    public function index(TasksRepository $tasksRepository): Response
    {
//        $tasks = $tasksRepository->getTaskDetails();
//
//        dd($tasks);
        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasksRepository->getTaskDetails(),
        ]);
    }

    #[Route('/new', name: 'app_tasks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TasksRepository $tasksRepository): Response
    {
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);

            return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tasks/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tasks_show', methods: ['GET'])]
    public function show(Tasks $task): Response
    {
        return $this->render('tasks/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tasks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);

            return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tasks/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tasks_delete', methods: ['POST'])]
    public function delete(Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $tasksRepository->remove($task, true);
        }

        return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/comment/new', name: 'app_comment_task', methods: ['GET', 'POST'])]
    public function commentTask(
                    Request $request,
                    CommentsRepository $commentsRepository,
                    Security $security, $id,
                    EntityManagerInterface $entityManager): Response
    {
        //Get userID login data
        $user = $security->getUser();


        if($user == null)
        {
            throw $this->createAccessDeniedException('You must Login');
        }


        $task = $entityManager->getRepository(Tasks::class)->find($id);


        if (!$task) {
            throw $this->createNotFoundException('Task not found');
        }



        $comment = new Comments();
        $comment->setUserId($user);
        $comment->setTaskId($task);

        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $commentsRepository->save($comment, true);

            return $this->redirectToRoute('app_comments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comments/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

}
