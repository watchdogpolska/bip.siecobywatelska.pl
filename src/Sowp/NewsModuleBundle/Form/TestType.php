<?php
namespace Sowp\NewsModuleBundle\Form;

use Exercise\HTMLPurifierBundle\Form\HTMLPurifierTransformer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestType extends AbstractType
{
    /** @var DataTransformerInterface Purifies HTML */
    private $purifier;

    public function __construct(DataTransformerInterface $transformer)
    {
        $this->purifier = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->purifier);
    }

    public function getParent()
    {
        return TextareaType::class;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
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
        return 'tinymce_textarea2';
    }
}