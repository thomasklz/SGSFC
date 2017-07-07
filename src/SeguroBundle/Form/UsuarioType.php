<?php

namespace SeguroBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UsuarioType extends AbstractType
{
    /**
     * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuario', TextType::class)
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Los campos de la contraseña deben emparejar',
                'first_options'  => array('label' => 'Contraseña'),
                'second_options' => array('label' => 'Repita Contraseña'),))
            ->add('idtipousuario', EntityType::class, array('label' => 'Tipo de Usuario', 'class' => 'SeguroBundle:Tipousuario',
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('u')
                    ->where ('u.tipo != :only')
                    ->setParameter('only', 'Administrador');
                },
                'choice_label'=>'tipo'
             ))
            ->add('save', SubmitType::class, array('label' => 'Crear Usuario'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SeguroBundle\Entity\Usuario'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'segurobundle_usuario';
    }


}
