<?php

namespace Sowp\MenuBundle\Command;

use Sowp\MenuBundle\Entity\MenuItem;
use Sowp\MenuBundle\Menu\ItemResolverChain;
use Sowp\MenuBundle\MenuManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SowpMenuRebuildCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sowp:menu:rebuild')
            ->setDescription('Revalidate and rebuild all dynamic menu elements')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $router = $this->getRouter();
        $resolver_chain = $this->getItemResolverChain();

        /** @var MenuItem[] $elements */
        $elements = $doctrine->getRepository(MenuItem::class)->getDynamicItemsOnly();
        foreach ($elements as $element){
            $repo = $doctrine->getRepository($element->getObjectClazz());
            $item = $repo->find($element->getObjectId());
            if(!$item){
                $output->writeln('Cannot find object: ' . $element);
            }
            $currentUrl = $resolver_chain->resolve($item);
            $oldUrl = $element->getUrl();
            if($currentUrl != $oldUrl){
                $output->writeln("Url changed:  $element. (old: $oldUrl, current: $currentUrl)");
                $element->setUrl($currentUrl);
                $em->persist($element);
            }
        }

        $em->flush();
        $output->writeln('Check finished.');
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected function getRouter()
    {
        return $this->getContainer()->get('router');
    }

    /**
     * @return ItemResolverChain
     */
    protected function getItemResolverChain()
    {
        return $this->getContainer()->get('sowp.menu.resolver_chain');
    }


}
