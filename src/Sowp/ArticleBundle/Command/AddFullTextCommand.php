<?php

namespace Sowp\ArticleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class AddFullTextCommand extends ContainerAwareCommand
{
    const ADD_KEY = 'add';
    const DROP_KEY = 'drop';

    protected function configure()
    {
        $this->setName('search_provider:fulltextindex:articlebundle');
        $this->setDescription('Add/Drop FULL TEXT index for Match Against in current bundle');

        $this->addOption(
            self::ADD_KEY,
            null,
            InputOption::VALUE_NONE,
            true
        );

        $this->addOption(
            self::DROP_KEY,
            null,
            InputOption::VALUE_NONE,
            true
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption(self::ADD_KEY)) {
            $this->addIndex($output);
        } elseif ($input->getOption(self::DROP_KEY)) {
            $this->dropIndex($output);
        } else {
            $output->writeln('use one of options: --add to add index, --drop to drop index');
        }
    }

    private function addIndex(OutputInterface $out)
    {
        $conn = $this->getConnection();

//        $out->writeln('Adding full text index to table `collection`:');

//        if ($conn->query('ALTER TABLE collection ADD FULLTEXT `search_indexes` (`name`)')) {
//            $out->writeln('Add on `collection` successfull...');
//        } else {
//            $out->writeln('Add on `collection` failed...');
//        }

        $out->writeln('Adding full text index to table `article`:');

        if ($conn->query('ALTER TABLE article ADD FULLTEXT `search_indexes` (`title`, `content`)')) {
            $out->writeln('Add on `article` successfull...');
        } else {
            $out->writeln('Add on `article` failed...');
        }
    }

    private function dropIndex(OutputInterface $out)
    {
        $conn = $this->getConnection();

//        $out->writeln('Dropping full text index in table `news_collection`');
//
//        if ($conn->query('ALTER TABLE collection DROP INDEX `search_indexes`')) {
//            $out->writeln('Drop on `collection` successfull...');
//        } else {
//            $out->writeln('Drop on `collections` failed...');
//        }

        $out->writeln('Dropping full text index to table `article`:');

        if ($conn->query('ALTER TABLE article DROP INDEX `search_indexes`')) {
            $out->writeln('Drop on `article` successfull...');
        } else {
            $out->writeln('Drop on `article` failed...');
        }
    }

    private function getConnection()
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getEntityManager()
            ->getConnection();
    }
}
