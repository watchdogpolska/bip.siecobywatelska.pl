<?php

namespace Sowp\RegistryBundle\Form;

use Sowp\RegistryBundle\Entity\Registry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = array(Registry::TYPE_TABLE, Registry::TYPE_LIST);
        $builder
            ->add('name')
            ->add('description')
            ->add('type', ChoiceType::class, array(
                'choices' => array_combine($types, $types)
            ))
            ->add('attributes', CollectionType::class, array(
                'entry_type' => AttributeType::class,
                'allow_add' => true,
                'by_reference' => false,
                'entry_options' => array(
                    'label' => false
                )
            ));
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sowp\RegistryBundle\Entity\Registry'
        ));
    }
}
