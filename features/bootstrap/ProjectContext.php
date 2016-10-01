<?php

use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelDictionary;

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
}
