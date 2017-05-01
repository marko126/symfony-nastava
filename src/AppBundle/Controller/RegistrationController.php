<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

class RegistrationController extends Controller
{
    public function registerAction(Request $request) 
    {
        
        // 1. Kreiramo objekte User i Form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        // 2. Proveramo da li je prihvacen request i da li su submitovani i validirani podaci
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            // 3. Kodiramo password
            $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            
            // 4. Sestanje podataka u bazu
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            $this->redirectToRoute('login');
        }
        
        // 5. Vracamo templejt sa podacima o formi
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
}