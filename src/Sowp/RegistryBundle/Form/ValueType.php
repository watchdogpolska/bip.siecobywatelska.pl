<?php

namespace Sowp\RegistryBundle\Form;

use Sowp\RegistryBundle\Entity\Attribute;
use Sowp\RegistryBundle\Entity\Value;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
                $form = $event->getForm();
                /** @var Value $data */
                $data = $event->getData();
                $type = $data->getType();
                if($type == Attribute::TYPE_FILE){
                    $form->add('path', FileType::class, array(
                        'label' => $data->getLabel(),
                        'data_class' => null,
                    ));
                }else{
                    $form->add('text', TextType::class, array(
                        'label' => $data->getLabel()
                    ));
                }
            });
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sowp\RegistryBundle\Entity\Value'
        ));
    }
}
