<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //Jms Serializer
            new JMS\SerializerBundle\JMSSerializerBundle(),

            // FOS User Bundle
            new FOS\UserBundle\FOSUserBundle(),

            // Doctrine Extensions
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle(),

            // Visual Extension
            new Tetranz\Select2EntityBundle\TetranzSelect2EntityBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),

            // Security Extension
            new Exercise\HTMLPurifierBundle\ExerciseHTMLPurifierBundle(),

            // Sowp - Entity Extension
            new Sowp\NewsModuleBundle\NewsModuleBundle(),
            new Sowp\ArticleBundle\SowpArticleBundle(),
            new Sowp\CollectionBundle\CollectionBundle(),

            // Sowp - Other Extension
            new Sowp\DashboardBundle\SowpDashboardBundle(),
            new Sowp\SearchModuleBundle\SearchModuleBundle(),
            new Sowp\ApiBundle\ApiBundle(),

            new AppBundle\AppBundle(),
            new Sowp\UploadBundle\UploadBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
