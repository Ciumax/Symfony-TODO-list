<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;
use App\Repository\TaskRepository;



class ToDoListController extends AbstractController
{
    
    /**
     * @Route("/to/do/list", name="app_to_do_list")
     */
    public function index(): Response
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();

        return $this->render('todo.html.twig', ['tasks'=>$tasks]);
    }

    /**
     * @Route("/to/do/create_task", name="create_task", methods={"POST"})
     */
    public function create(Request $request)
    {
        
        $title = trim($request->request->get('title'));
        if(empty($title))
        return $this->redirectToRoute('app_to_do_list');
        $entityManager = $this->getDoctrine()->getManager();

        $task = new Task;
        $task->setTitle($title);
        $entityManager->persist($task);
        $entityManager->flush(); 

        return $this->redirectToRoute('app_to_do_list');

    }

    /**
     * @Route("/to/do/task_delete/{id}", name="task_delete")
     */
    public function delete(Task $id)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($id);
        $entityManager->flush();

        return $this->redirectToRoute('app_to_do_list');

    }

    /**
     * @Route("/to/do/update/{id}", name="upd_to_do_list")
     */
    public function updateReady(Task $id)
    {
        
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();
        return $this->render('updt.html.twig', ['tasks'=>$tasks]);


    }

    /**
     * @Route("/to/do/task_update/", name="task_update",  methods={"POST"})
     */
    public function update(Request $request)
    {
        
        $id = basename($_SERVER['HTTP_REFERER']);
        $title = trim($request->request->get('title'));
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);
        if(empty($title))
        return $this->redirectToRoute('app_to_do_list');

        $task->setTitle($title);
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('app_to_do_list');
       
    }

}


