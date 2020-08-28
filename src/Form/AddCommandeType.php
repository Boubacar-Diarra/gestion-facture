<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Produit;
use App\Entity\Commande;
use Doctrine\ORM\EntityManager;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AddCommandeType extends AbstractType
{
    private $manager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('etat',ChoiceType::class, ['choices' => ['En attente' => 'En attente', 'Confirmer' => 'Confirmer']])
            ->add('client',ChoiceType::class, [
                'choices' =>
                    $this->manager->getRepository(Client::class)->findAll(),
                'choice_value' => 'id',
                'choice_label' => function (?Client $client){
                    return $client->getNom() . ' ' .  $client->getPrenom();
                }
                ])
            ->add('produits',ChoiceType::class,[
                    'mapped' => false,'multiple'=>true,
                    'choices' => $this->manager->getRepository(Produit::class)->findAll(),
                    'choice_value' => 'id',
                    'choice_label' => function (?Produit $produit) {return $produit->getDesignation();}
                ])
            ->add('qte',null, ['attr' => ['placeholder'=> 'Exemple: 5-2-4-6']]
            )
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
