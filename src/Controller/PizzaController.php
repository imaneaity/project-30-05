<?php

namespace App\Controller;

use App\Repository\PizzaRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PizzaController extends AbstractController
{
    #[Route('/', name: 'app_pizza_home')]
    public function home(PizzaRepository $repo): Response
    {
        //recup de la liste des pizzas depuis la bd
        $pizzas= $repo->findAll();

        //affichage
        return $this->render('pizza/home.html.twig', [
            'pizzas' => $pizzas,
        ]);
    }


    
}
