<?php
namespace App\Form;

use App\Entity\PreuvesDossier;
use App\Entity\Dossier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PreuvesDossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $lawyer = $options['lawyer'];

        $builder
            ->add('preuves', TextareaType::class)
            ->add('dossier', EntityType::class, [
                'class' => Dossier::class,
                'choice_label' => 'titre',
                'query_builder' => function ($repo) use ($lawyer) {
                    return $repo->createQueryBuilder('d')
                        ->join('d.lawyers', 'l')
                        ->where('l.id = :lawyer')
                        ->setParameter('lawyer', $lawyer->getId());
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PreuvesDossier::class,
            'lawyer' => null,
        ]);
    }
}
