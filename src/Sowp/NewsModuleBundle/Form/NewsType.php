<?php
namespace Sowp\NewsModuleBundle\Form;
use Sowp\CollectionBundle\Form\CollectionAutocompleteFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType as Select2;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class NewsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['label' => 'Tytuł'])
            ->add('content', TinyMceType::class, [
                'label' => 'Treść',
                'required' => false,
            ])
            ->add('attachments', CollectionType::class, [
                'label' => 'Załączniki',
                'entry_type' => AttachmentType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
            ])
            ->add('pinned', null, ['label' => 'Przypięty'])
            ->add('collections', CollectionAutocompleteFormType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                if ($event->getData()->getId() !== null) {
                    $event->getForm()->add('modifyNote', TextareaType::class, [
                        'label' => 'Nota Edytorska',
                        'attr' => [
                            'rows' => 2,
                        ],
                    ]);
                }
            });
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sowp\NewsModuleBundle\Entity\News',
        ));
    }
}