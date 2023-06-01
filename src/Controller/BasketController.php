<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Entity\Article;
use App\Repository\BasketRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted("ROLE_USER")]
class BasketController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_basket_display')]
    public function display(): Response
    {
        return $this->render('basket/display.html.twig');
    }


    #[Route('/mon-panier/{id}/ajouter', name: 'app_basket_addArticle')]
    public function addArticle(Pizza $pizza, BasketRepository $repository): Response
    {
        //recuperer l'utilisateur de son panier
        $user = $this->getUser();
        $basket = $user->getBasket();

        //créer un nouvel article à mettre dans le panier
        $article = new Article();
        $article->setQuantity(1);
        $article->setBasket($basket);
        $article->setPizza($pizza);

        //ajouter l'article au panier
        $basket->addArticle($article);

        //sauvegarde du panier dans la bd
        $repository->save($basket, true);

        //redirection vers le panier
        return $this->redirectToRoute("app_basket_display") ;

    }
}
