<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    // Return the list of users
    #[Route(path: '/users', name: 'user_list')]
    #[IsGranted('ROLE_ADMIN')]
    public function listAction(
        UserRepository $repository
    )
    {
        $users = $repository->findAll();

        return $this->render('user/list.html.twig', ['users' => $users]);
    }

    // Add a new user
    #[Route(path: '/users/create', name: 'user_create')]
    #[IsGranted('ROLE_ADMIN')]
    public function createAction(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $userPasswordHasher
    )
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // TODO tester le hash du mdp
            dump($form->get('password')->getData());
            $hashPassword = $userPasswordHasher->hashPassword($user, $form->get('password')->getData());
            dd($hashPassword);
            $user->setPassword($hashPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            dump($form);
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    // Edit an existing user
    #[Route(path: '/users/{id}/edit', name: 'user_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function editAction(
        User $user,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $userPasswordHasher
    )
    {
        $form = $this->createForm(UserType::class, $user, [
            'submit_label' => 'Modifier l\'utilisateur'
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {

            // TODO tester le hash du mdp
            dump($form->get('password')->getData());
            $hashPassword = $userPasswordHasher->hashPassword($user, $form->get('password')->getData());
            dd($hashPassword);
            $user->setPassword($hashPassword);

            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
