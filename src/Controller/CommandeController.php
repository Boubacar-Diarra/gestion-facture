<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CmdProduit;
use App\Entity\Facture;
use App\Form\AddCommandeType;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
     /**
     * @Route("/home/ajout-commande", name="ajout.commande")
     */
    public function ajoutCommande(Request $request, SessionInterface $session)
    {
        $session->set('errorMessage', "une erreur s'est produit");
        #1 : creation ddu fomulaire
        $commande = new Commande();
        $manager=$this->getDoctrine()->getmanager();
        $form = $this->createForm(AddCommandeType::class, $commande);
        $form->handleRequest($request);
        $f = new Facture();
        #2 : modification eventuelle du formualire
        if($form->isSubmitted()and $form->isvalid()){
            try {
                $manager->persist($commande);
                $manager->flush();
                $produits= $form->get('produits')->getData();

                $qtes = preg_split('#-#', $commande->getQte());

                if(count($produits) != count($qtes)){
                    
                    $session->set('error', true);
                    $session->set('errorMessage', "Le nombre de produit choisi est different du nombre qte");
                    throw new \Exception();
                }
                for ($i = 0; $i < count($qtes); $i++){
                    if($produits[$i]->getQteStock() < (int)$qtes[$i]){
                        $session->set('error', true);
                        $session->set('errorMessage', "La quantité en stock est insuffisante: ". $produits[$i]->getDesignation());
                        throw new \Exception();
                    }else{
                        $f->setMontant($f->getMontant() + $qtes[$i] * $produits[$i]->getPrix());
                        $f->setEtat("Non payer");
                        $f->setCreatedAt(new \DateTime());

                        $cm = new CmdProduit();
                        $cm->setId($commande->getId() . '' . $produits[$i]->getId());
                        $cm->setCommande($commande);
                        $cm->setProduit($produits[$i]);
                        $cm->setQte($qtes[$i]);
                        $commande->addCommandeProduit($cm);
                        #$produits[$i]->addCommandeProduit($cm);

                        $produits[$i]->setQteStock($produits[$i]->getQteStock() - (int)$qtes[$i]);

                        $commande->setFacture($f);
                        $f->setMontantRestant($f->getMontant());
                        $manager->persist($cm);
                        $manager->persist($commande);
                        $manager->persist($produits[$i]);
                    }
                }
                $manager->flush();
                $session->set('error', true);
                $session->set('errorMessage', "Ajout effectué avec succès");
                return $this->redirectToRoute('home');
            }catch (\Exception $exception){
                $manager->remove($commande);
                $manager->flush();
                $session->set('error', true);
            }
            
        }
        $session->set('error', true);
        #3 : retourner le formulaire en tant que variable
        return $this->redirectToRoute('home');
    }
     /**
     * @Route("/home/modif-commande-{id}", name="modif.commande")
     */
    public function modif(Commande $commande, Request $request, SessionInterface $session){
        $manager = $this->getDoctrine()->getManager();

        $cmdP = $commande->getCommandeProduits();
        $commande->setQte($cmdP[0]->getQte());
        for ($i = 1; $i < count($cmdP); $i++){
                $commande->setQte($commande->getQte() . '-' . $cmdP[$i]->getQte());
        }
        $i = 0;
        foreach ($commande->getCommandeProduits() as $cmdProduit)
        {
            $p = $cmdProduit->getProduit();
            $p->setQteStock($p->getQteStock() + $cmdProduit->getQte());
           # $p->removeCommandeProduit($cmdProduit);
            #$cmdProduit->setProduit(null);
            $manager->persist($p);
        }

        $form=$this->createForm(AddCommandeType::class,$commande);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isvalid()) {
            #suppression des anciennes données
            $tab = $commande->getCommandeProduits();
            foreach ($tab as $cmdProduit) {
                $commande->removeCommandeProduit($cmdProduit);
                $cmdProduit->setCommande(null);
                $manager->remove($cmdProduit);
                #$manager->flush();
            }
            $f = $commande->getFacture();
            $commande->getFacture()->removeCommande($commande);
            $commande->setFacture(null);
            $manager->remove($f);
            #$manager->flush();

            #$manager->flush();
            $f = new Facture();
            #ajout des nouvelles données
            try {
                #verification des données
                $produits = $form->get('produits')->getData();

                $qtes = preg_split('#-#', $commande->getQte());

                if (count($produits) != count($qtes)) {

                    $session->set('error', true);
                    $session->set('errorMessage', "Le nombre de produit choisi est different du nombre qte");
                    return $this->render('commande/modifier.twig', ['form' => $form->createView()]);
                }
                for ($i = 0; $i < count($qtes); $i++) {
                    if ($produits[$i]->getQteStock() < (int)$qtes[$i] || $qtes[$i] == 0) {
                        $session->set('error', true);
                        $session->set('errorMessage', "La quantité en stock est insuffisante: ". $produits[$i]->getDesignation());
                        return $this->render('commande/modifier.twig', ['form' => $form->createView()]);
                    }
                }
                #on confirme les suppression avant les nouveau ajout
                $manager->flush();
                for ($i = 0; $i < count($qtes); $i++) {
                        $f->setMontant($f->getMontant() + $qtes[$i] * $produits[$i]->getPrix());
                        $f->setEtat("Non payer");
                        $f->setCreatedAt(new \DateTime());

                        $cm = new CmdProduit();
                        $cm->setId($commande->getId() . '' . $produits[$i]->getId());
                        $cm->setCommande($commande);
                        $cm->setProduit($produits[$i]);
                        $cm->setQte($qtes[$i]);
                        $commande->addCommandeProduit($cm);
                        #$produits[$i]->addCommandeProduit($cm);

                        $produits[$i]->setQteStock($produits[$i]->getQteStock() - (int)$qtes[$i]);

                        $commande->setFacture($f);
                        $f->setMontantRestant($f->getMontant());
                        $manager->persist($cm);
                        $manager->persist($commande);
                        $manager->persist($produits[$i]);
                }
                $manager->flush();
                $session->set('error', true);
                $session->set('errorMessage', "Modification effectuée avec succès");
                return $this->redirectToRoute('home');
            } catch (\Exception $exception) {
                $session->set('error', true);
                $session->set('errorMessage', "Une erreur c'est produit");
            }
        }
        return $this->render('commande/modifier.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/home/supprimer-commande-{id}", name="supprimer.commande")
     */
    public function supprimer(commande $commande, SessionInterface $session){
        $manager = $this->getDoctrine()->getManager();
        if(count($commande->getCommandeProduits()) == 0)
        {
            $manager->remove($commande);
            $manager->flush();
            $session->set('error', true);
            $session->set('errorMessage', "Commande supprimé avec succès");
            #3 : retourner le formulaire en tant que variable

        }else{
            $session->set('error', true);
            $session->set('errorMessage', "Cette commande ne peut pas etre supprimer car il est lié a d'autre infos");
        }
        return $this->redirectToRoute('home');
    }
    /**
     * @Route("/home/confirmer-commande-{id}", name="confirmer.commande")
     */
    public function confimer(commande $commande, SessionInterface $session){
        $manager = $this->getDoctrine()->getManager();
        $commande->setEtat(Commande::Confirmer);
        $commande->getFacture()->setCreatedAt(new \DateTime());
        $manager->persist($commande->getFacture());
        $manager->persist($commande);
        $manager->flush();
        $session->set('error', true);
        $session->set('errorMessage', "Commande confimer avec succès");
        return $this->redirectToRoute('home');
    }
}
