<?php

namespace Continuous\Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class CliContext implements Context, SnippetAcceptingContext
{
    const BIN_PATH = './agent';

    /**
     * @var string
     */
    protected $cwd;

    /**
     * @var array
     */
    protected $lastOutput;

    /**
     * @var int
     */
    protected $lastReturn;
    
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I am in the :cwd folder
     * @param $cwd
     */
    public function setCwd($cwd)
    {
        $this->cwd = $cwd;
    }

    /**
     * @When I run :command
     * @param $command
     */
    public function execute($command)
    {
        $this->lastOutput = [];
        $this->lastReturn = null;
        $command = "cd " . $this->cwd . " && " . $command;
        exec($command, $this->lastOutput, $this->lastReturn);
    }

    /**
     * @Then the exit code should be :return
     * @param $return
     */
    public function lastReturnIs($return)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            $return,
            $this->lastReturn,
            "The command has not returned $return but $this->lastReturn:" . PHP_EOL . implode(PHP_EOL, $this->lastOutput) 
        );
    }
    
}
