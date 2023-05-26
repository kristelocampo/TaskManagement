<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(
        UserRepository $userRepository,
        Security $security,
        AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $user = $security->getUser();

        if($user == null)
        {
            throw $this->createAccessDeniedException('You must Login');
        }

        $userDetails = $userRepository->findUserByEmail($user->getUserIdentifier());

        if ($authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin');
        }
        elseif ($authorizationChecker->isGranted('ROLE_USER')){
            return $this->redirectToRoute('app_profile');
        }

        return $this->redirectToRoute('app_login');

    }
    #[Route('/profile', name: 'app_profile', methods: ['GET'])]
    public function userLogin(UserRepository $userRepository, Security $security): Response
    {
        $user = $security->getUser();

        if($user == null)
        {
            throw $this->createAccessDeniedException('You must Login');
        }


        $userProject = $userRepository->getProjectById($user->getUserIdentifier());


       // dd($userProject);

        return $this->render('user/user.html.twig', [
            'userProject' => $userProject
        ]);
    }
    #[Route('/admin', name: 'app_admin', methods: ['GET'])]
    public function adminLogin(UserRepository $userRepository, Security $security): Response
    {
        $user = $security->getUser();

        if($user == null)
        {
            throw $this->createAccessDeniedException('You must Login');
        }

        $userDetails = $userRepository->findUserByEmail($user->getUserIdentifier());

        return $this->render('user/admin.html.twig', [
            'user' => $userDetails,
        ]);
    }

    #[Route('/list', name: 'app_user_list', methods: ['GET'])]
    public function user(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
