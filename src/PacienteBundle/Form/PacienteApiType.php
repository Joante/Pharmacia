<?php

 namespace PacienteBundle\Form;

 use Symfony\Component\Form\AbstractType;
 use Symfony\Component\Form\FormBuilderInterface;
 use Symfony\Component\OptionsResolver\OptionsResolver;

 class PacienteApiType extends PacienteType{
	
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PacienteBundle\Entity\Paciente',
            'csrf_protection' => false
        ));
    }
     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }
 }