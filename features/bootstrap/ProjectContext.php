<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelDictionary;

require_once __DIR__.'/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

/**
 * Created by PhpStorm.
 * User: andrzej
 * Date: 28.09.16
 * Time: 10:35.
 */
class ProjectContext implements Context
{
    use KernelDictionary;
    use DoctrineDictrionary;

    /** @var Behat\MinkExtension\Context\MinkContext */
    private $minkContext;

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        /** @var InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();

        $this->minkContext = $environment->getContext('Behat\MinkExtension\Context\MinkContext');
    }

    /**
     * @Given /^the database is clean$/
     */
    public function truncateAllTables()
    {
        $em = $this->getManager();
        $connection = $em->getConnection();
        $sm = $connection->getSchemaManager();

        $tables = $sm->listTables();

        $connection->query('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tables as $table) {
            $tableName = $table->getName();
            $connection->query("TRUNCATE TABLE `${tableName}`");
        }
        $connection->query('SET FOREIGN_KEY_CHECKS = 1;');
    }

    /**
     * @Then /^I should see a table with (\d+) rows$/
     */
    public function iShouldSeeATableWithRows($rowCount)
    {
        $tbl = $this->minkContext->getSession()->getPage()->find('css', 'table.table');
        assertNotNull($tbl, 'Cannot find a table!');
        assertCount(intval($rowCount), $tbl->findAll('css', 'tbody tr'));
    }
}
