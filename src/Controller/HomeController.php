<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(TasksRepository $tasksRepository, EntityManagerInterface $entityManager): Response
    {

        $tasksRepository = $entityManager->getRepository(Tasks::class);
        $taskDetails = $tasksRepository->getTasksWithDetails();

        return $this->render('home/index.html.twig', [
            'taskDetails' => $taskDetails,
        ]);
    }
}
