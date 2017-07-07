<?php

namespace SeguroBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReunionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('temas', TextType::class, array("required"=>"required", "label"=>"Tema" ))
                ->add('descripcion', TextareaType::class, array("required"=>"required", "label"=>"Descripción" ))
                ->add('valorreunion', TextType::class, array("required"=>"required", "label"=>"Valor reunion" ))
                ->add('valormulta', TextType::class, array("required"=>"required", "label"=>"Valor Multa" ))
                ->add('save', SubmitType::class, array('label' => 'Registrar'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SeguroBundle\Entity\Reunion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'segurobundle_reunion';
    }


}
