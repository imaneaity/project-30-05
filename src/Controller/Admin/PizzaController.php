<?php

namespace App\Controller\Admin;

use App\Entity\Pizza;
use App\Form\PizzaType;
use App\Repository\PizzaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[IsGranted('ROLE_ADMIN')]
class PizzaController extends AbstractController
{

    #[Route('/admin', name: 'app_admin_pizza_home')]
    public function home(): Response
    {
        return $this->render('admin/pizza/home.html.twig');
    }
    
    #[Route('/admin/pizza/nouvelle', name: 'app_admin_pizza_create')]
    public function create(Request $request, PizzaRepository $repo): Response
    {
        //créer le form
        $form = $this->createForm(PizzaType::class);
        $form->handleRequest($request);

        //tester si le form est envoyé et valid
        if($form->isSubmitted() && $form->isValid()){
            //save la pizza dans la bd
            $repo->save($form->getData(), true);

            //redirection vers la liste.....
            return $this->redirectToRoute('app_admin_pizza_list');

        }

        return $this->render('admin/pizza/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/admin/pizza', name: 'app_admin_pizza_list')]
    public function list(PizzaRepository $repo): Response
    {
        $pizzas = $repo->findAll();
        return $this->render('admin/pizza/list.html.twig', [
            'pizzas' => $pizzas
        ]);

    }


    #[Route('/admin/pizza/{id}/modifier', name: 'app_admin_pizza_update')]
    public function update(Pizza $pizza,Request $request, PizzaRepository $repo): Response
    {
        //créer le form
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);

        //tester si le form est envoyé et valid
        if($form->isSubmitted() && $form->isValid()){
            //save la pizza dans la bd
            $repo->save($form->getData(), true);

            //redirection vers la liste.....
            return $this->redirectToRoute('app_admin_pizza_list');

        }

        return $this->render('admin/pizza/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/admin/pizza/{id}/supprimer', name: 'app_admin_pizza_delete')]
    public function delete(Pizza $pizza, PizzaRepository $repo): Response
    {
        $repo->remove($pizza, true);

        //redirection vers la liste des pizzas
        return $this->redirectToRoute('app_admin_pizza_list');
    }
}
