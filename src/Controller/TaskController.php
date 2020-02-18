<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Symfony\Component\Security\Core\User\UserInterface;
class TaskController extends AbstractController
{
  
    public function index()
    {
        /*$em = $this->getDoctrine()->getManager();
        $user_repo = $this->getDoctrine()->getRepository(User::class);
        $users = $user_repo->findAll();
        */
        $task_repo = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $task_repo->findBy([], [
            'id'=> 'desc'
        ]);
        /* desde task
       
        foreach ($tasks as $tarea) {
            echo $tarea->getUser()->getName()." ".$tarea->getUser()->getSurname()
            ." :".$tarea->getTitle()."<br>";
        }*/
        /*foreach ($users as $usuario) {
            echo "<h1>".$usuario->getName()." ".$usuario->getSurname()."</h1>";
            foreach ($usuario->getTasks() as $tarea) {
                echo $tarea->getTitle()."<br>";
            }
        }*/
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    public function tarea_detalle(Task $tarea){
        if(!$tarea){
            return $this->redirectToRoute('index');
        }
        return $this->render('task/task_detail.html.twig', [
            'tarea_get' => $tarea,
        ]);
    }

    public function create(Request $solicitud, UserInterface $usuario){
        $tarea = new Task();
        $formulario = $this->createForm(TaskType::class, $tarea);
        $formulario->handleRequest($solicitud);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $tarea->setCreatedAt(new \Datetime('now'));
            $tarea->setUser($usuario);

            $em = $this->getDoctrine()->getManager();
            $em->persist($tarea);
            $em->flush();

            return $this->redirect($this->generateUrl('tarea_detalle', ['tarea' => $tarea->getId()]));
        }

        return $this->render('task/new_task.html.twig', [
            'tarea_form' => $formulario->createView()
        ]);
    }

    public function mytasks(UserInterface $usuario){
        $tasks = $usuario->getTasks();
				
		return $this->render('task/my-tasks.html.twig',[
			'tasks' => $tasks 
		]);	
    }

    public function edit(Request $request, UserInterface $user, Task $task){
		if(!$user || $user->getId() != $task->getUser()->getId()){
			return $this->redirectToRoute('index');
		}
		
		$form = $this->createForm(TaskType::class, $task);
		
		$form->handleRequest($request);
		
		if($form->isSubmitted() && $form->isValid()){
			//$task->setCreatedAt(new \Datetime('now'));
			//$task->setUser($user);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($task);
			$em->flush();
			
			return $this->redirect($this->generateUrl('tarea_detalle', ['tarea' => $task->getId()]));
		}
		
		return $this->render('task/new_task.html.twig',[
			'edit' => true,
			'tarea_form' => $form->createView()
		]);
	}
	
	public function delete(UserInterface $user, Task $task){
		if(!$user || $user->getId() != $task->getUser()->getId()){
			return $this->redirectToRoute('index');
		}
		
		if(!$task){
			return $this->redirectToRout('index');
		}
		
		$em = $this->getDoctrine()->getManager();
		$em->remove($task);
		$em->flush();
		
		return $this->redirectToRoute('index');
	}


}
