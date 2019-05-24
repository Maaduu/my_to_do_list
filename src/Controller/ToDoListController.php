<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="to_do_list")
     */
    public function index()
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([], ['id' => 'DESC']);
        return $this->render('index.html.twig', ['tasks' => $tasks]);
    }

    //CREATE TASK
    /**
     * @Route("/create", name="create_task", methods={"POST"})
     */
    public function create(Request $request)
    {
        $title = trim($request->request->get('title'));

        if (empty($title))
            return $this->redirectToRoute('to_do_list');

        $em = $this->getDoctrine()->getManager();

        $task = new Task;
        $task->setTitle($title);

        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('to_do_list');
    }

    //TASK STATUS
    /**
     * @Route("/switch-status/{id}", name="switch_status")
     */

    public function switchStatus($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository(Task::class)->find($id);

        $task->setStatus(!$task->getStatus());
        $em->flush();

        return $this->redirectToRoute('to_do_list');
    }

    //DELETE TASK
    /**
     * @Route("/delete/{id}", name="delete_task")
     */

    public function delete(Task $id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return $this->redirectToRoute('to_do_list');
    }
}
