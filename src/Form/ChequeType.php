<?php

namespace App\Form;

use App\Entity\Cheque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChequeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('montant')
            ->add('intitule', null, ['label'=> 'Intitulé de la banque'])
            ->add('proprietaire')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cheque::class,
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
