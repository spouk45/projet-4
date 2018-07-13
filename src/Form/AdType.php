<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('title', null, ['label'=> 'Titre'])
            ->add('adContent', TextareaType::class, [
                'label' => 'Contenu de l\'annonce',
            ])
            ->add('price', null, ['label'=>'Prix'])
            ->add('city', null, ['label'=>'Ville'])
            ->add('postalCode', null, ['label'=>'Code postal'])
            ->add('adress', null, ['label'=>'Adresse'])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
