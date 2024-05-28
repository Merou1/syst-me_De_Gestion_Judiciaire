<?php
// src/Form/DossierType.php
namespace App\Form;

use App\Entity\Dossier;
use App\Entity\Lawyer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateDepot', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('documents', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('partiesImpliquees', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lawyers', EntityType::class, [
                'class' => Lawyer::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dossier::class,
        ]);
    }
}
