<?php

namespace App\Form;

use App\Entity\Restorer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RestorerRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['attr' => [
                'placeholder' => "Nom",
            ]])
            ->add('file', FileType::class, [
                'data_class' => null,
                'required' => false
            ])
            ->add('address', TextType::class, ['attr' => [
                'placeholder' => "Adresse",
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Restorer::class,
        ]);
    }
}
