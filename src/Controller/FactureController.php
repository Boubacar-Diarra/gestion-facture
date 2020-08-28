<?php

namespace App\Controller;

use App\Entity\Cheque;
use App\Entity\Facture;
use App\Form\ChequeType;
use App\Form\ModifFactureType;
use App\Repository\ChequeRepository;
use Doctrine\ORM\EntityManager;
use App\Entity\InfoPaiementEspece;
use App\Form\AddPaiementEspeceType;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FactureController extends AbstractController
{
    private $manager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }

    /**
     * @Route("/facture/payer-espece-{id}", name="payer.espece.facture", defaults = {"id" = 0})
     */
    public function payerEspece(FactureRepository $factureRepository, Request $request, SessionInterface $session)
    {
        $id = (int)$request->get('id');
        $facture = $factureRepository->find($id);
        $infoPaiement = new InfoPaiementEspece();
        $infoPaiement->setCreatedAt(new \DateTime());
        $form = $this->createForm(AddPaiementEspeceType::class,$infoPaiement);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            if($facture->getMontantRestant() >= $infoPaiement->getMontant())
                $facture->setMontantRestant($facture->getMontantRestant() - $infoPaiement->getMontant());
            else{
                $session->set('error', true);
                $session->set('errorMessage', "Le montant est supérieur au montant de la facture ");
                return $this->redirectToRoute('home');
            }
            if($facture->getMontantRestant() == 0) {
                $facture->setEtat(Facture::TOTALITE);
            }
            else {
                $facture->setEtat(Facture::PARTIELLEMENT);
            }
            $facture->addInfoPaiementEspece($infoPaiement);
            $infoPaiement->setFacture($facture);
            $this->manager->persist($facture);
            $this->manager->persist($infoPaiement);
            $this->manager->flush();
            $session->set('error', true);
            $session->set('errorMessage', "Paiement effectué avec succès");
        }else{
            $session->set('error', true);
            $session->set('errorMessage', "Une erreur c'est produite");
        }
        return $this->redirectToRoute('home');
    }
    /**
     * @Route("/facture/payer-cheque-{id}", name="payer.cheque.facture", defaults={"id" = 0})
     */
    public function payerCheque(FactureRepository $factureRepository, Request $request, SessionInterface $session, ChequeRepository $chequeRepository)
    {
        $id = (int)$request->get('id');
        $facture = $factureRepository->find($id);
        $cheque = new Cheque();
        $cheque->setCreatedAt(new \DateTime());
        $form = $this->createForm(ChequeType::class,$cheque);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            if($chequeRepository->find($cheque->getNumero()))
            {
                $session->set('error', true);
                $session->set('errorMessage', "Un chèque portant ce numéro existe déjà ");
                return $this->redirectToRoute('home');
            }
            if($facture->getMontantRestant() >= $cheque->getMontant())
                $facture->setMontantRestant($facture->getMontantRestant() - $cheque->getMontant());
            else{
                $session->set('error', true);
                $session->set('errorMessage', "Le montant du chèque est supérieur au montant de le facture ");
                return $this->redirectToRoute('home');
            }
            if($facture->getMontantRestant() == 0) {
                $facture->setEtat(Facture::TOTALITE);
            }
            else {
                $facture->setEtat(Facture::PARTIELLEMENT);
            }
            $facture->addCheque($cheque);
            $cheque->setFacture($facture);
            $this->manager->persist($facture);
            $this->manager->persist($cheque);
            $this->manager->flush();
            $session->set('error', true);
            $session->set('errorMessage', "Paiement effectué avec succès");
        }else{
            $session->set('error', true);
            $session->set('errorMessage', "Une erreur c'est produite");
        }
        return $this->redirectToRoute('home');
    }
    /**
     * @Route("/facture/modifier-{id}", name="modif.facture")
     */
    public function modifier(Facture $facture, Request $request, SessionInterface $session)
    {
        $form = $this->createForm(ModifFactureType::class,$facture);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            if($facture->getMontantRestant() == 0)
            {
                $facture->setEtat(Facture::TOTALITE);
            }
            else {
                $facture->setEtat(Facture::PARTIELLEMENT);
            }
            $this->manager->persist($facture);
            $this->manager->flush();
            $session->set('error', true);
            $session->set('errorMessage', "Modification effectué avec succès");
            return $this->redirectToRoute('home');
        }else{
            $session->set('error', true);
            $session->set('errorMessage', "Une erreur c'est produite");
        }
        return $this->render('facture/modifier.html.twig');
    }


    /**
     * @Route("/facture/print-{id}", name="print.facture")
     */
    public function print(Facture $facture)
    {
        return $this->render('facture/print.html.twig', ['c' =>  $facture]);
    }
}
