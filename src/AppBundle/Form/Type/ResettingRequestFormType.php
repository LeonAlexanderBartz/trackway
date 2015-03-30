<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ResettingRequestFormType
 *
 * @package AppBundle\Form\Type
 */
class ResettingRequestFormType extends AbstractOverridableFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', $this->overrideOptions('email', ['label' => 'user.entity.email', 'required' => true], $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\User', 'override' => false, 'validation_groups' => ['resetting']]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_resetting_request_form_type';
    }
}