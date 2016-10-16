<?php

namespace Sowp\RegistryBundle\Form;

use Sowp\RegistryBundle\Entity\Attribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = array(Attribute::TYPE_TEXT, Attribute::TYPE_FILE);
        $builder
            ->add('name')
            ->add('description')
            ->add('type', ChoiceType::class, array(
                'choices' => array_combine($types, $types)
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sowp\RegistryBundle\Entity\Attribute'
        ));
    }
}
