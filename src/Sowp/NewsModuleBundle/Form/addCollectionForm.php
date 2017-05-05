<?php

namespace Sowp\NewsModuleBundle\Form;

use Sowp\CollectionBundle\Form\CollectionAutocompleteFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType as Select2;

class addCollectionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('public');
        $builder->add('parent', CollectionAutocompleteFormType::class, [
            'multiple' => false,
            'placeholder' => 'Wybierz tag/kolekcję nadrzędną',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sowp\NewsModuleBundle\Entity\Collection',
        ));
    }
}
