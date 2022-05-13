<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession() ;
        if(!$session->has('todos')){
            $todos = [
                'achat' => 'acheter cle usb'
            ] ;
            $session->set('todos',$todos) ;
            $this->addFlash('info','La liste est initialisee');
        }
        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
        ]);
    }

    #[Route('/todo/add/{name}/{content}',name:'todo.add')]
    public function addTodo(Request $request, $name, $content):RedirectResponse{
        $session = $request->getSession() ;
        if($session->has('todos')){
            $todos = $session->get('todos') ;
            if(isset($todos[$name])){
                $this->addFlash('error',"le todo $name existe deja") ;
            }
            else{
                $todos[$name] = $content ;
                $session->set('todos',$todos) ;
                $this->addFlash('success','todo ajoutee') ;
            }
        }
        else{
            $this->addFlash('error','la liste n\'est pas encore initialisee') ;
        }

        return $this->redirectToRoute('app_todo');
    }


    #[Route('/todo/edit/{name}/{content}',name:'todo.edit')]
    public function editTodo(Request $request, $name, $content):RedirectResponse{
        $session = $request->getSession() ;
        if($session->has('todos')){
            $todos = $session->get('todos') ;
            if(!isset($todos[$name])){
                $this->addFlash('error',"le todo $name n'existe pas") ;
            }
            else{
                $todos[$name] = $content ;
                $session->set('todos',$todos) ;
                $this->addFlash('success','todo modifie') ;
            }
        }
        else{
            $this->addFlash('error','la liste n\'est pas encore initialisee') ;
        }

        return $this->redirectToRoute('app_todo');
    }
    #[Route('/todo/delete/{name}',name:'todo.delete')]
    public function deleteTodo(Request $request, $name):RedirectResponse{
        $session = $request->getSession() ;
        if($session->has('todos')){
            $todos = $session->get('todos') ;
            if(!isset($todos[$name])){
                $this->addFlash('error',"le todo $name n'existe pas") ;
            }
            else{
                unset($todos[$name]) ;
                $session->set('todos',$todos) ;
                $this->addFlash('success','todo supprime') ;
            }
        }
        else{
            $this->addFlash('error','la liste n\'est pas encore initialisee') ;
        }

        return $this->redirectToRoute('app_todo');
    }

    #[Route('/todo/reset',name:'todo.reset')]
    public function resetTodo(Request $request):RedirectResponse{
        $session = $request->getSession() ;
        $session->remove('todos') ;
        $this->addFlash('success','la liste est reset') ;
        return $this->redirectToRoute('app_todo') ;
    }
}
