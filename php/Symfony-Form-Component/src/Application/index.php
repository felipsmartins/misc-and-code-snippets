<?php 
namespace Application;

require 'vendor/autoload.php';

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


// $formFactory = Forms::createFormFactory();



class Product extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', array(
                'class' => 'Application\Entity\Product',
                'placeholder' => 'Selecinar',
            ))            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\InvoiceItem',
        ));
    }

    public function getBlockPrefix()
    {
        return 'invoice_product';
    }
}