<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\RegistrationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
  
    public function registration(Request $request, ManagerRegistry $doctrine, UserPasswordEncoderInterface $encoder){
        $manager = $doctrine->getManager();
        
        $admin = new Admin();
        $form = $this->createForm(RegistrationType::class, $admin);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $admin->setCreatedAt(new \DateTimeImmutable()); //Donnée envoyée à la base de données en plus du contenu du formulaire
            // encode password before persist
            $hash = $encoder->encodePassword($admin, $admin->getPassword());

            $admin->setPassword($hash);
            $manager->persist($admin);          
            $manager->flush();
            //Redirect to login interface
            return $this->redirectToRoute('security_login');
        }
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(){
        //Le traitement est fait par symfony
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){
        
    }

}
