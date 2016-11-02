<?php

namespace Sowp\NewsModuleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType as Select2;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class NewsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['label' => 'Tytuł'])
            ->add('content', null, ['label' => 'Treść'])
            ->add('attachments', CollectionType::class, [
                'label' => 'Załączniki',
                'entry_type' => AttachmentType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'required'     => false
            ])
            ->add('pinned', null, ['label' => 'Przypięty'])
            ->add('collections', Select2::class, [
                'multiple' => true,
                'class' => 'Sowp\NewsModuleBundle\Entity\Collection',
                'remote_route' => 'sowp_news_collection_query_select2',
                'primary_key' => 'id',
                'language' => 'en',
                'placeholder' => 'Wybierz tagi jakie będzie posiadał news',
                'cache' => true,
                'cache_timeout' => 60000,
                'label' => 'Tagi'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sowp\NewsModuleBundle\Entity\News'
        ));
    }

}
