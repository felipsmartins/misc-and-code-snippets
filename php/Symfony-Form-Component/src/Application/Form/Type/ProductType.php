<?php

namespace Application\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('category', EntityType::class, [
            'class' => 'Application\Entity\Category',
            'choice_label' => 'name',
        ]);

        //$builder->add('category', ChoiceType::class, [
        //    'choices' => $this->loadProducts($options['categories'])
        //]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['categories' => [],]); # custom form option
    }

    public function loadProducts($categoryCollection)
    {
        $choices = [];

        foreach ($categoryCollection as $category) {
            $choices[$category->getId()] = $category->getName();
        }

        return $choices;
    }
}