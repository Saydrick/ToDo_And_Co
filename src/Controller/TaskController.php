<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
    // Return the list of tasks
    #[Route(path: '/tasks', name: 'task_list')]
    #[IsGranted('ROLE_USER')]
    public function listAction(
        TaskRepository $repository,
        Security $security
    ): Response {
        $user = $security->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $tasks = $repository->findNotCompletedByAdmin($user);
        } else {
            $tasks = $repository->findNotCompletedByUser($user);
        }

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    // Return the list of completed tasks
    #[Route(path: '/tasks/completed', name: 'done_task_list')]
    #[IsGranted('ROLE_USER')]
    public function listCompletedTask(
        TaskRepository $repository,
        Security $security
    ): Response {
        $user = $security->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $tasks = $repository->findCompletedByAdmin($user);
        } else {
            $tasks = $repository->findCompletedByUser($user);
        }

        return $this->render('task/doneList.html.twig', ['tasks' => $tasks]);
    }

    // Create a new task
    #[Route(path: '/tasks/create', name: 'task_create')]
    #[IsGranted('ROLE_USER')]
    public function createAction(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    // Edit an existing task
    #[Route(path: '/tasks/{id}/edit', name: 'task_edit')]
    #[IsGranted('ROLE_USER')]
    public function editAction(
        Task $task,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $autor = $task->getUser();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($autor);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    // Validate task
    #[Route(path: '/tasks/{id}/toggle', name: 'task_toggle')]
    #[IsGranted('ROLE_USER')]
    public function toggleTaskAction(
        Task $task,
        EntityManagerInterface $em
    ): Response {
        $task->toggle(!$task->isDone());
        $em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    // Delete task
    #[Route(path: '/tasks/{id}/delete', name: 'task_delete')]
    #[IsGranted('ROLE_USER')]
    public function deleteTaskAction(
        Task $task,
        EntityManagerInterface $em
    ): Response {
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
