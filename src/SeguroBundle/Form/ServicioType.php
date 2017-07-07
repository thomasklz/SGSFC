<?php

namespace SeguroBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ServicioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('servicio',TextType::class, array("required"=>"required",'label'=>'Nombre del servicio'))
                ->add('valor',TextType::class, array("required"=>"required",'label' => 'Valor'))
                ->add('save', SubmitType::class, array('label' => 'Registrar'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SeguroBundle\Entity\Servicio'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'segurobundle_servicio';
    }


}
