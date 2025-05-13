<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;

class DoctrineContext implements KernelAwareContext
{
    use ManagerAwareTrait;

    /**
     * @Then flush doctrine
     */
    public function iFlushDoctrine()
    {
        $this->getManager()->flush();
    }

    /**
     * @Given table :tableName has rows
     */
    public function insertRows($tableName, TableNode $table)
    {
        $connection = $this->getManager()->getConnection();
        foreach ($table->getHash() as $rows) {
            $sets = [];
            foreach ($rows as $key => $value) {
                if ('NULL' == $value) {
                    $sets[$key] = sprintf('%s = NULL', $key);
                } else {
                    $sets[$key] = sprintf('%s = "%s"', $key, $value);
                }
            }
            $sql = sprintf('INSERT INTO %s SET %s', $tableName, join(', ', $sets));
            $connection->executeQuery($sql);
        }
    }

    /**
     * @Given table :tableName is empty
     */
    public function emptyTable($tableName)
    {
        $connection = $this->getManager()->getConnection();
        $statement = $connection->prepare(sprintf('TRUNCATE TABLE %s', $tableName));
        $statement->execute();
    }
}
