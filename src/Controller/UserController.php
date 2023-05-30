<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/inscription', name: 'app_user_registration')]
    public function registration(Request $request, UserPasswordHasherInterface $hasher, UserRepository $repository): Response
    {
        //création du formulaire
        $form = $this->createForm(RegistrationType::class);

        //remplir le formulaire avec les données de l'utilisateur
        $form->handleRequest($request);

        // vérification si le form est envoyé et est valide
        if($form->isSubmitted() && $form->isValid()) {
            //recuperer les données de l'utilisateur
            $user = $form->getData();

            //crypter le mdp
            $cryptedPass = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($cryptedPass);

            //enregistrer l'utilisateur dans la base
            $repository->save($user, true);

            //redirection...
        }
        //affichage du formulaire
        return $this->render('user/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
