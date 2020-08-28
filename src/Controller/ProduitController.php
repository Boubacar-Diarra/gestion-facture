<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Entity\ProduitFournisseur;
use App\Repository\CommandeProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
  /**
     * @Route("/home/ajout-produit", name="ajout.produit")
     */
    public function ajoutProduit(Request $request, SessionInterface $session)
    {
        $manager=$this->getDoctrine()->getmanager();
        #1 : creation ddu fomulaire
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        #2 : modification eventuelle du formualire
        if($form->isSubmitted() and $form->isvalid()){
            try {
                #
                $manager->persist($produit);
                $manager->flush();
                #
                $pf = new ProduitFournisseur();
                $pf->setProduit($produit);
                $pf->setQte($produit->getQteStock());
                $pf->setMontant($produit->getPrixAchat() * ($produit->getQteStock()));
                $fournisseur= $form->get('fournisseur')->getData();
                dump($fournisseur);
                #$pf->setFournisseur($fournisseur);
                $fournisseur->addProduitFournisseur($pf);
                $pf->setId($fournisseur->getId().''.$produit->getId());
                $manager->persist($produit);
                $manager->persist($pf);
                $manager->flush();
                $session->set('error', true);
                $session->set('errorMessage', "Ajout effectué avec succès");
                return $this->redirectToRoute('home');
            }catch (\Exception $exception){
                $manager->remove($produit);
                $manager->persist($produit);
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
     * @Route("/home/modif-produit-{id}", name="modif.produit")
     */
    public function modif(produit $produit, Request $request, SessionInterface $session, CommandeProduitRepository $commandeProduitRepository){
        $manager = $this->getDoctrine()->getManager();
        $pf = $produit->getProduitFournisseurs()[0];
        $produit->setQteStock($pf->getQte());
        $totalCommande = 0;
        $produit->setPrixAchat($pf->getMontant()/$pf->getQte());
        $form=$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()) {

            $qteAF = $produit->getQteStock();
            $pf->setQte($qteAF);
            #calcule de commande total
            foreach ($commandeProduitRepository->findAll() as $cmdProduit){
                if($cmdProduit->getProduit()->getId() == $produit->getId())
                    $totalCommande += $cmdProduit->getQte();
            }
            $produit->setQteStock($qteAF - $totalCommande);

            $pf->setMontant($qteAF * $produit->getPrixAchat());
            $manager->persist($produit);
            $manager->flush();
            $session->set('error', true);
            $session->set('errorMessage', "Modification effectuée  avec succès");
            return $this->redirectToRoute('home');
        }
        return $this->render('produit/modifier.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/home/supprimer-produit-{id}", name="supprimer.produit")
     */
    public function supprimer(Produit $produit, CommandeProduitRepository $commandeProduitRepository ,SessionInterface $session){
        $manager = $this->getDoctrine()->getManager();
        foreach ($commandeProduitRepository->findAll() as $cmdProduit){
            if($cmdProduit->getProduit()->getId() == $produit->getId())
            {
                $session->set('error', true);
                $session->set('errorMessage', "Ce produit ne peut pas etre supprimer car il est lié a d'autres commandes"); 
                return $this->redirectToRoute('home'); 
            }
        }

        if(count($produit->getProduitFournisseurs()) == 0)
        {
            $manager->remove($produit);
            $manager->flush();
            $session->set('error', true);
            $session->set('errorMessage', "produit supprimé avec succès");
            #3 : retourner le formulaire en tant que variab

        }else{
            $session->set('error', true);
            $session->set('errorMessage', "Ce produit ne peut pas etre supprimer car il est lié a d'autre infos");
        }
        return $this->redirectToRoute('home');
    }
}
