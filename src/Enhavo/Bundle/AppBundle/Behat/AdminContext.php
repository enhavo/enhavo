<?php
/**
 * AdminContext.php
 *
 * @since 05/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AdminBundle\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

class AdminContext extends \PHPUnit_Framework_TestCase implements Context, SnippetAcceptingContext
{
    /**
     * @Given I am in a directory :arg1
     */
    public function iAmInADirectory($arg1)
    {

    }

    /**
     * @When I call :arg1
     */
    public function iCall($arg1)
    {

    }

    /**
     * @Then I will received:
     */
    public function iWillReceived(PyStringNode $string)
    {

    }

}