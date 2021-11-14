<?php

namespace App\Form;

use App\Entity\UrlEntry;
use App\Helpers\UrlShorter\UrlShortAlgorythm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UrlEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('realUrl')
            ->add('algorythm', ChoiceType::class, ['choices' => UrlShortAlgorythm::getList()]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UrlEntry::class,
            'algorythm' =>  null
        ]);
    }
}
