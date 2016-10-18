<?php

namespace Sowp\RegistryBundle\Twig;

use Sowp\RegistryBundle\WebPathResolver;

class WebPathExtension extends \Twig_Extension
{
    /** @var WebPathResolver */
    private $resolver;

    public function __construct(WebPathResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('web_path', [$this, 'webPathFunction'])
        );
    }

    public function webPathFunction($path)
    {
        return $this->resolver->resolve($path);
    }

    public function getName()
    {
        return 'sowp_registry_extension';
    }
}