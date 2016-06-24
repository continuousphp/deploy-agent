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

    /**
     * Checks whether last command output contains provided string.
     *
     * @Then the output should contain:
     *
     * @param PyStringNode $text PyString text instance
     */
    public function theOutputShouldContain(PyStringNode $text)
    {
        \PHPUnit_Framework_Assert::assertContains($this->getExpectedOutput($text), $this->getOutput());
    }

    private function getExpectedOutput(PyStringNode $expectedText)
    {
        $text = strtr($expectedText, array('\'\'\'' => '"""', '%%TMP_DIR%%' => sys_get_temp_dir() . DIRECTORY_SEPARATOR));

        // windows path fix
        if ('/' !== DIRECTORY_SEPARATOR) {
            $text = preg_replace_callback(
                '/[ "]features\/[^\n "]+/', function ($matches) {
                return str_replace('/', DIRECTORY_SEPARATOR, $matches[0]);
            }, $text
            );
            $text = preg_replace_callback(
                '/\<span class\="path"\>features\/[^\<]+/', function ($matches) {
                return str_replace('/', DIRECTORY_SEPARATOR, $matches[0]);
            }, $text
            );
            $text = preg_replace_callback(
                '/\+[fd] [^ ]+/', function ($matches) {
                return str_replace('/', DIRECTORY_SEPARATOR, $matches[0]);
            }, $text
            );
        }

        return $text;
    }

    private function getOutput()
    {
        $output = implode("\n", $this->lastOutput);

        // Normalize the line endings in the output
        if ("\n" !== PHP_EOL) {
            $output = str_replace(PHP_EOL, "\n", $output);
        }
        
        // Remove ANSI control chars
        $output = preg_replace('/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[m|K]/', '', $output);

        // Replace wrong warning message of HHVM
        $output = str_replace('Notice: Undefined index: ', 'Notice: Undefined offset: ', $output);

        return trim(preg_replace("/ +$/m", '', $output));
    }

    /**
     * Checks whether specified file exists and contains specified string.
     *
     * @Then /^"([^"]*)" file should contain:$/
     *
     * @param string       $path file path
     * @param PyStringNode $text file content
     */
    public function fileShouldContain($path, PyStringNode $text)
    {
        \PHPUnit_Framework_Assert::assertFileExists($path);

        $fileContent = trim(file_get_contents($path));
        // Normalize the line endings in the output
        if ("\n" !== PHP_EOL) {
            $fileContent = str_replace(PHP_EOL, "\n", $fileContent);
        }

        \PHPUnit_Framework_Assert::assertContains($this->getExpectedOutput($text), $fileContent);
    }
}
