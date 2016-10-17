<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\WebAssert;
use Behat\Symfony2Extension\Context\KernelDictionary;

require_once __DIR__.'/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

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
     * Returns Mink session assertion tool.
     *
     * @param string|null $name name of the session OR active session will be used
     *
     * @return WebAssert
     */
    public function assertSession($name = null)
    {
        return $this->minkContext->assertSession($name);
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
     * @Then /^I should see a table (\".+?\"|[^ ]+) with body:$/
     */
    public function iShouldSeeATableTableInfo($selector, TableNode $node)
    {
        $this->fixSelector($selector);

        $table = $this->minkContext->getSession()->getPage()->find('css', $selector);
        assertNotNull($table, "Table \"$selector\" not found");

        $expectedData = array_values($node->getTable());

        /** @var NodeElement[] $rows */
        $rows = $table->findAll('css', 'tr');

        assertSame(count($expectedData), count($rows));
        for ($i = 0; $i < count($expectedData); $i++) {
            $dataRow = $expectedData[$i];
            $row = $rows[$i];

            /** @var NodeElement[] $cells */
            $cells = $row->findAll('css', 'td, th');
            assertCount(count($dataRow), $cells);
            for ($j = 0; $j < count($dataRow); $j++) {
                $cellData = $dataRow[$j];
                $cell = $cells[$j];
                $text = $cell->getText();
                if (!empty($cellData)) {
                    assertContains($cellData, $text);
                }
            }
        }
    }

    /**
     * @Then the header :name should be equal to :value
     */
    public function theHeaderShouldBeEqualTo($name, $value)
    {
        $this->assertSession()->responseHeaderEquals($name, $value);
    }

    /**
     * @Then the header :name should contain :value
     */
    public function theHeaderShouldContain($name, $value)
    {
        $this->assertSession()->responseHeaderContains($name, $value);
    }

    public function fixSelector(&$selector)
    {
        if($selector[0] == '"' && substr($selector, -1) == '"'){
            $selector = substr($selector, 1, -1);
        }
    }
}
