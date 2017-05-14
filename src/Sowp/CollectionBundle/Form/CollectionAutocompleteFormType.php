<?php

namespace Sowp\CollectionBundle\Form;

use Sowp\CollectionBundle\Entity\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class CollectionAutocompleteFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'multiple' => true,
            'remote_route' => 'admin_collections_query_select2',
            'text_property' => 'title',
            'class' => Collection::class,
            'cache' => true,
            'cache_timeout' => 60000, // if 'cache' is true
            'language' => 'en',
            'allow_add' => array(
                'enabled' => true,
            ),
        ]);

    }

    public function getParent()
    {
        return Select2EntityType::class;
    }


}