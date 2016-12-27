<?php

namespace Sowp\NewsModuleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * html purify field type.
 */
class TinyMceType extends AbstractType
{
    /** @var DataTransformerInterface Purifies HTML */
    private $purifier;

    public function __construct(DataTransformerInterface $purifier)
    {
        $this->purifier = $purifier;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->purifier);
    }

    public function getParent()
    {
        return TextareaType::class;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => false,
            'attr' => [
                'rows' => 25,
                'id' => 'news_content',
                'data-wysiwyg' => '',
            ],
        ));
    }

    public function getName()
    {
        return 'tinymce_textarea';
    }
}
