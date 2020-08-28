<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
     /**
     * @Route("/home/ajout-client", name="ajout.client")
     */
    public function ajoutClient(Request $request, SessionInterface $session){
        $client = new Client();
        $manager=$this->getDoctrine()->getManager();
        $form=$this->createForm(ClientType::class,$client);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            try{
            $manager->persist($client);
            $manager->flush();
            $session->set('erreur',true);
            $session->set('errorMessage', "Ajout effectué avec succès");
            return $this->redirectToRoute('home');
            }catch (\Exception $exception) {
                $manager->remove($client);
                $manager->persist($client);
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
     * @Route("/home/modif-client-{id}", name="modif.client")
     */
    public function modif(Client $client, Request $request, SessionInterface $session){
        $form=$this->createForm(ClientType::class,$client);
        $form->handleRequest($request);
        $manager = $this->getDoctrine()->getManager();
        if($form->isSubmitted() and $form->isValid()) {
            $manager->persist($client);
            $manager->flush();
            $session->set('error', true);
            $session->set('errorMessage', "Modification effectuée  avec succès");
            return $this->redirectToRoute('home');
        }
        return $this->render('client/modifier.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/home/supprimer-client-{id}", name="supprimer.client")
     */
    public function supprimer(Client $client, SessionInterface $session){
        $manager = $this->getDoctrine()->getManager();
        if(count($client->getCommandes()) == 0)
        {
            $manager->remove($client);
            $manager->flush();
            $session->set('error', true);
            $session->set('errorMessage', "Client supprimé avec succès");
            #3 : retourner le formulaire en tant que variable

        }else{
            $session->set('error', true);
            $session->set('errorMessage', "Ce client ne peut pas etre supprimer car il est lié a d'autre infos");
        }
        return $this->redirectToRoute('home');
    }
}