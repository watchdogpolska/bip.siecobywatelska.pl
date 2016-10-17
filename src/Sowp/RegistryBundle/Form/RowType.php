<?php

namespace Sowp\RegistryBundle\Form;

use Sowp\RegistryBundle\Entity\Attribute;
use Sowp\RegistryBundle\Entity\Row;
use Sowp\RegistryBundle\Entity\Value;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $form = $event->getForm();
                /** @var Row $row */
                $row = $event->getData();

                $allAttrs = $row->getRegistry()->getAttributes();
                $currentValues = $row->getValues();
                $allAttrs->forAll(function($i, Attribute $attr) use ($row, $currentValues) {
                    $exists = $currentValues->exists(function($i, Value $value) use ($attr){
                        return $value->getAttribute() == $attr;
                    });

                    if(!$exists){
                        $row->addValue($attr->createValue());
                    }
                    return true;
                });

                $form->add('values', CollectionType::class, array(
                    'entry_type' => ValueType::class,
                    'allow_add' => false,
                    'allow_delete' => false,
                    'label' => false,
                    'entry_options' => array(
                        'label' => false,
                    )
                ));
            });
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sowp\RegistryBundle\Entity\Row'
        ));
    }
}
