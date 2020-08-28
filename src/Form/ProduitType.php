<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Fournisseur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProduitType extends AbstractType
{
    private $manager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation')
            ->add('type')
            ->add('prix', null, ['label' => 'Prix de vente'])
            ->add('prix_achat', null, ['label' => "Prix d'achat"])
            ->add('qteStock', null, ['label' => 'Quantité achetée chez le fournisseur'])
            ->add('fournisseur',ChoiceType::class,[
                'mapped' => false,
                'choices' => $this->manager->getRepository(Fournisseur::class)->findAll(),
            'choice_value' => 'id',
            'choice_label' => function (?Fournisseur $statut) {return $statut->getDesignation();}
])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
