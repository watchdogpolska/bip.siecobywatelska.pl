<?php

namespace Sowp\NewsModuleBundle\Form;

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
        $builder->add('parent', Select2::class, [
            'multiple' => false,
            'class' => 'Sowp\NewsModuleBundle\Entity\Collection',
            'remote_route' => 'admin_collections_query_select2',
            'primary_key' => 'id',
            'language' => 'en',
            'placeholder' => 'Wybierz tag/kolekcję nadrzędną',
            'cache' => true,
            'cache_timeout' => 60000,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sowp\NewsModuleBundle\Entity\Collection',
        ));
    }
}
