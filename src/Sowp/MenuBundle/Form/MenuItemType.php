<?php

namespace Sowp\MenuBundle\Form;

use Sowp\MenuBundle\Entity\MenuItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name');
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var MenuItem $item */
            $item = $event->getData();
            $form = $event->getForm();

            if ($item->getObjectClazz() == null) {
                $form->add('url');
            }
        });
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $item = $event->getData();
            $form = $event->getForm();

            if (null != $item->getId()) {
                $form->add('parent');
            }
        });


    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sowp\MenuBundle\Entity\MenuItem'
        ));
    }
}
