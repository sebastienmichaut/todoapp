<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\ToDoList;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController{

    /**
     * @Route("/task/createTask/{id}")
     */
    public function newTask(Request $request, ToDoList $list): Response{
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task->setList($list)
                ->setCompleted(0);
            $em->persist($task);
            $em->flush();
            
            return $this->redirectToRoute("Welcome");
        }

        return $this->render('task/createTask.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/task/updateTask/{id}")
     */
    public function update(Request $request, Task $updateTask): Response{
        $form = $this->createForm(TaskType::class, $updateTask);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("Welcome");
        }

        return $this->render("task/createTask.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/task/deleteTask/{id}")
     */
    public function delete(Task $delete): Response{
        $em = $this->getDoctrine()->getManager();
        $em->remove($delete);
        $em->flush();
        
        return $this->redirectToRoute("Welcome");
    }

    /**
     * @Route("/checkbox/{id}")
     */

    public function checkBox(Task $task): Response {
            $task->setCompleted(!$task->getCompleted());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect("/toDoList/read/{$task->getList()->getId()}");
        }


            



}