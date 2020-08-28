<?php

namespace App\Controller;

use App\Entity\Cheque;
use App\Form\ChequeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChequeController extends AbstractController
{
    /**
     * @Route("/home/ajout-cheque", name="ajout.cheque")
     */
    public function ajoutCheque(Request $request, SessionInterface $session)
    {
        $cheque = new Cheque();
        $manager=$this->getDoctrine()->getManager();
        $form=$this->createForm(ChequeType::class,$cheque);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            try{
            $manager->persist($cheque);
            $manager->flush();
            $session->set('erreur',true);
            $session->set('errorMessage', "Ajout effectué avec succès");
            return $this->redirectToRoute('home');
            }catch (\Exception $exception) {
                $manager->remove($cheque);
                $manager->persist($cheque);
                $session->set('error', true);
                $session->set('errorMessage', "Une erreur c'est produit");
            }
        }
        $session->set('error', true);
        $session->set('errorMessage', "Une erreur c'est produit");
        #3 : retourner le formulaire en tant que variable
        return $this->redirectToRoute('home');
    }
        
}