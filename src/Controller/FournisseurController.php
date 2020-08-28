<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\AddFournisseurType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FournisseurController extends AbstractController
{
      /**
     * @Route("/home/ajout-fournisseur", name="ajout.fournisseur")
     */
    public function ajoutFournisseur(Request $request, SessionInterface $session){
        $fournisseur = new Fournisseur();
        $manager=$this->getDoctrine()->getmanager();
        $form=$this->createForm(AddFournisseurType::class,$fournisseur);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isvalid()){
            try{
            $manager->persist($fournisseur);
            $manager->flush();
            $session->set('error', true);
            $session->set('errorMessage', "Ajout effectué avec succès");
            return $this->redirectToRoute('home');
        }catch (\Exception $exception){
            $manager->remove($fournisseur);
            $manager->persist($fournisseur);
            $session->set('error', true);
            $session->set('errorMessage', "Une erreur c'est produit");

        }
    }
    $session->set('error', true);
    $session->set('errorMessage', "Une erreur c'est produit");
    #3 : retourner le formulaire en tant que variable
    return $this->redirectToRoute('home');
    }
        /**
     * @Route("/home/modif-fournisseur-{id}", name="modif.fournisseur")
     */
    public function modif(Fournisseur $fournisseur, Request $request, SessionInterface $session){
        $form=$this->createForm(AddFournisseurType::class,$fournisseur);
        $form->handleRequest($request);
        $manager = $this->getDoctrine()->getManager();
        if($form->isSubmitted() and $form->isValid()) {
            $manager->persist($fournisseur);
            $manager->flush();
            $session->set('error', true);
            $session->set('errorMessage', "Modification effectuée  avec succès");
            return $this->redirectToRoute('home');
        }
        return $this->render('fournisseur/modifier.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/home/supprimer-fournisseur-{id}", name="supprimer.fournisseur")
     */
    public function supprimer(Fournisseur $fournisseur, SessionInterface $session){
        $manager = $this->getDoctrine()->getManager();
        if(count($fournisseur->getProduitFournisseurs()) == 0)
        {
            $manager->remove($fournisseur);
            $manager->flush();
            $session->set('error', true);
            $session->set('errorMessage', "Fournisseur supprimé avec succès");
            #3 : retourner le formulaire en tant que variable

        }else{
            $session->set('error', true);
            $session->set('errorMessage', "Ce fournisseur ne peut pas etre supprimer car il est lié a d'autre infos");
        }
        return $this->redirectToRoute('home');
    }
}
