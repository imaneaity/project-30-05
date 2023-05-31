<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
            return $this->redirectToRoute('app_pizza_home');
        }
        //affichage du formulaire
        return $this->render('user/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
