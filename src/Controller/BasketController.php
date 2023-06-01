<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Entity\Article;
use App\Repository\BasketRepository;
use App\Repository\ArticleRepository;
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


    #[Route('/mon-panier/{id}/plus', name: 'app_basket_plus')]
    public function plus(Article $article, ArticleRepository $repository): Response
    {
        //ajouter +1 à la quantité
        $qt = $article->getQuantity();
        $article->setQuantity($qt+1);

        //save dans la bd
        $repository->save($article, true);

        //redirection vers le panier
        return $this->redirectToRoute("app_basket_display");
    }


    
    #[Route('/mon-panier/{id}/moins', name: 'app_basket_minus')]
    public function minus(Article $article, ArticleRepository $repository, BasketRepository $basketRepo): Response
    {
        //mettre la quantité à -1
        $qt = $article->getQuantity();
        $article->setQuantity($qt-1);

        //tester si la qt est à 0
        if($article->getQuantity() <= 0) {
            //supprimer l'article du panier
            //1. recup l'utilisateur puis son panier
            $user= $this->getUser();
            $basket= $user->getBasket();

            //2.supprimer de l'entité basket l'article
            $basket->removeArticle($article);

            //màj du panier dans la bd
            $basketRepo->save($basket, true);

            //redirection vers le panier
            return $this->redirectToRoute("app_basket_display");
        }
        //save dans la bd
        $repository->save($article, true);

        //redirection vers le panier
        return $this->redirectToRoute("app_basket_display");
    }

}
