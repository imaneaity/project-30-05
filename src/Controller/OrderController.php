<?php

namespace App\Controller;

use App\DTO\Payment;
use App\Entity\Order;
use App\Form\PaymentType;
use App\Repository\OrderRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/commander', name: 'app_order_display')]
    public function display(Request $request, OrderRepository $repo, ArticleRepository $articleRepo): Response
    {
        //recup l'utilisateur
        $user = $this->getUser();
        //initialiser le paiement
        $payment= new Payment();

        //recup l'adresse de l'utilisateur pour la commande
        $payment->address= $user->getAddress();

        //création du formulaire
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        //tester si le form est envoyé et est valid
        if($form->isSubmitted() && $form->isValid())
        {
            //création de la commande
            $order = new Order();
            $order->setUser($user);
            $order->setAddress($payment->address);

            //transfrer les articles du panier vers la commande
            foreach($user->getBasket()->getArticles() as $article)
            {
                $order->addArticle($article);
            }

            //supprimer l'article du panier
            foreach($user->getBasket()->getArticles() as $article){ 
               
                $user->getBasket()->removeArticle($article);
                $articleRepo->remove($article, true);
            }

            //sauvegarde dans la bd
            $repo->save($order, true);
            //redirection vers la page de validation
            return $this->redirectToRoute('app_order_validate',[
                'id' => $order->getId()
            ]);
        }

        return $this->render('order/display.html.twig', [
            'form' => $form,
        ]);
    }



    #[Route('/commander/{id}/validation', name: 'app_order_validate')]
    public function validate(Order $order): Response
    {
        return $this->render('order/validate.html.twig',[
            'order' =>$order,
        ]);
    }
}
