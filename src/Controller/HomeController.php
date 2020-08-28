<?php

namespace App\Controller;

use App\Entity\Cheque;
use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\ChequeType;
use App\Form\ClientType;
use App\Form\ProduitType;
use App\Entity\Fournisseur;
use App\Form\AddCommandeType;
use App\Form\ModifFactureType;
use App\Form\AddFournisseurType;
use App\Entity\InfoPaiementEspece;
use App\Form\AddPaiementEspeceType;
use App\Repository\ChequeRepository;
use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use App\Repository\ProduitFournisseurRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\FournisseurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    public const TVA = 0.1;
    /**
     * @Route("/", name="home")
     */
    public function index(CommandeRepository $commandeRepository, ClientRepository $clientRepository, ProduitRepository  $produitRepository, FournisseurRepository $fournisseurRepository,ChequeRepository $chequeRepository, FactureRepository $factureRepository, ProduitFournisseurRepository $produitFournisseurRepository)
    {
        $produits = $produitRepository->findAll();
        $tva = 0;
        $today = (new \DateTime())->getTimestamp();
        $produitRupture = [];
        foreach ($produits as $p){
            if($today >= $p->getCreatedAt()->getTimestamp() and $p->getCreatedAt()->getTimestamp() >($today - 3600*24*30*3))
                $tva += ( $p->getProduitFournisseurs()[0]->getMontant() /  $p->getProduitFournisseurs()[0]->getQte());
            if($p->getQteStock() == 0)
                $produitRupture[] = $p;
        }
        $tva = $tva*self::TVA;
        $facture = $factureRepository->findAll();
        $cheque = $chequeRepository->findAll();
        $factureRetard = [];
        foreach($facture as $f) {
            if (($f->getCreatedAt()->getTimestamp() + 3600 * 24 * 30) < (new \DateTime())->getTimestamp())
            {
                $factureRetard[] = $f;
            }
        }

        $produitDemande=[];
        $N=0;
        foreach($produitFournisseurRepository->findAll() as $pf){
            $N+=$pf->getQte();
        }
        $okAdd = true;
        foreach($produits as $p){
            $p->setQteTemp($p->getProduitFournisseurs()[0]->getQte());
            foreach ($produitDemande as $pd){
                if($pd->getDesignation() == $p->getDesignation() ){
                    $pd->setQteTemp($pd->getQteTemp() + $p->getQteTemp());
                    $okAdd = false;
                }
            }
            if($okAdd)
                $produitDemande[]=$p;
        }
        foreach($produitDemande as $pd){
            $pd->setFrequence(($pd->getQteTemp()/$N)*100);
        }
        usort($produitDemande,function ($a,$b){
            if($a->getFrequence()>$b->getFrequence())
            {
                return -1;
            }
            if($a->getFrequence()<$b->getFrequence()){
                return 1;
            }
            return 0;
        });

        $client = new Client();
        $fournisseur = new Fournisseur();
        $commande = new Commande();
        $produit = new Produit();
        $cheque = new Cheque();
        $infoPaiement = new InfoPaiementEspece();
        $form = $this->createForm(ClientType::class,$client,['action'=>$this->generateUrl('ajout.client')]);
        $formCheque = $this->createForm(ChequeType::class,$cheque,['action'=>$this->generateUrl('payer.cheque.facture')]);
        $formfournisser = $this->createForm(AddFournisseurType::class,$fournisseur,['action'=>$this->generateUrl('ajout.fournisseur')]);
        $formCommande = $this->createForm(AddCommandeType::class,$commande,['action'=>$this->generateUrl('ajout.commande')]);
        $formProduit = $this->createForm(ProduitType::class,$produit,['action'=>$this->generateUrl('ajout.produit')]);
        $formPaiment = $this->createForm(AddPaiementEspeceType::class,$infoPaiement, ['action' => $this->generateUrl('payer.espece.facture')]);

        return $this->render('home/index.html.twig',[
            'clients' => $clientRepository->findAll(),
            'cheques'=>$chequeRepository->findAll(),
            'produits' => $produits,
            'fournisseurs' => $fournisseurRepository->findAll(),
            'facture' => $factureRepository->findAll(),
            'commandes' =>$commandeRepository->findAll(),
            'formClient' => $form->createView(),
            'formFournisseur' => $formfournisser->createView(),
            'formCommande' => $formCommande->createView(),
            'formProduit'=>$formProduit->createView(),
            'formCheque'=>$formCheque->createView(),
            'produitsRupture' => $produitRupture,
            'facturesRetard' => $factureRetard,
            'formPaiment' => $formPaiment->createView(),
            'produitsDemande' => $produitDemande,
            'tva' => $tva
        ]);
    }
}