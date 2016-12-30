<?php

namespace Sowp\ArticleBundle\Form;

use Sowp\ArticleBundle\Entity\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType as FormCollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content', WysiwygType::class)
            ->add('attachments', FormCollectionType::class, array(
                'entry_type' => AttachmentType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
//                'delete_empty' => true,
            ))
            ->add('editNote')
            ->add('collection', Select2EntityType::class, [
                'multiple' => true,
                'remote_route' => 'admin_article_collection_query',
                'text_property' => 'name',
                'class' => Collection::class,
                'cache' => true,
                'cache_timeout' => 60000, // if 'cache' is true
                'language' => 'en',
                'allow_add' => array(
                    'enabled' => true,
                ),
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sowp\ArticleBundle\Entity\Article',
        ));
    }
}
