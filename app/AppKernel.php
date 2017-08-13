<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
	public function registerBundles()
	{
		$bundles = [
			# Framework
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\MonologBundle\MonologBundle(),
			new Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),

			# Serializers
			new JMS\SerializerBundle\JMSSerializerBundle(),

			# Database
			new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
			new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
			new SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle(),
			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

			# Visual extension
			new Tetranz\Select2EntityBundle\TetranzSelect2EntityBundle(),
			new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),

			# Registration
			new FOS\UserBundle\FOSUserBundle(),

			# Security
			new Exercise\HTMLPurifierBundle\ExerciseHTMLPurifierBundle(),

			# Sowp
			new Sowp\ApiBundle\ApiBundle(),
			new Sowp\ArticleBundle\SowpArticleBundle(),
			new Sowp\CollectionBundle\CollectionBundle(),
			new Sowp\DashboardBundle\SowpDashboardBundle(),
//			new Sowp\NewsBundle\NewsBundle(),
			new Sowp\NewsModuleBundle\NewsModuleBundle(),
			new Sowp\SearchModuleBundle\SearchModuleBundle(),
			new Sowp\UploadBundle\UploadBundle(),

			new AppBundle\AppBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
			$bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
			$bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
			$bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
	        $bundles[] = new DAMA\DoctrineTestBundle\DAMADoctrineTestBundle();
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
