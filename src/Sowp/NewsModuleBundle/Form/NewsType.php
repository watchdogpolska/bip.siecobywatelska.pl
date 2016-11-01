<?php

namespace Sowp\NewsModuleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('attachments')
            ->add('pinned')
            ->add('modifyNote')
            ->add('collections', Select2::class, [
                'multiple' => true,
                'class' => 'Sowp\NewsModuleBundle\Entity\Collection',
                'remote_route' => 'sowp_news_collection_query_select2',
                'primary_key' => 'id',
                'language' => 'en',
                'placeholder' => 'Wybierz tagi jakie będzie posiadał news',
                'cache' => true,
                'cache_timeout' => 60000,
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
