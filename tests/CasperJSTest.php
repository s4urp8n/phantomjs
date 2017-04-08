<?php

use Zver\CasperJS;
use Zver\DirectoryWalker;

class CasperJSTest extends PHPUnit\Framework\TestCase
{

    use \Zver\Package\Helper;

    public static function setUpBeforeClass()
    {
        clearstatcache(true);
    }

    public static function tearDownAfterClass()
    {
        clearstatcache(true);
    }

    public function testIsCasperJSInstalled()
    {
        $installed = CasperJS::isCasperJSInstalled();

        if (!$installed) {
            $this->fail("CasperJS is not installed. Test aborting.\n");
        }
    }

    public function testCommandGeneration()
    {
        $testData = [

            [
                'path'      => 'script.path',
                'arguments' => ['arg1', 'arg2', 'arg3'],
                'options'   => ['opt1', 'opt2', 'opt3'],
                'command'   => 'casperjs opt1 opt2 opt3 "script.path" arg1 arg2 arg3',
            ],

        ];

        foreach ($testData as $test) {
            $this->assertSame($test['command'], CasperJS::getCasperJsCommand($test['path'], $test['arguments'], $test['options']));
        }
    }

    public function testIsPhantomJSInstalled()
    {
        $installed = CasperJS::isPhantomJSInstalled();

        if (!$installed) {
            $this->fail("PhantomJS is not installed. Test aborting.\n");
        }
    }

    public function testExecuteScript()
    {
        $this->assertSame(trim(CasperJS::executeScript(static::getPackagePath('/tests/files/testExecute.js'))), 'Hello world');
    }

    public function testExecuteArguments()
    {
        $arguments = ['1', '2', '3', '4324', 'http://site.com/'];

        $this->assertSame(CasperJS::executeScript(static::getPackagePath('/tests/files/testArguments.js'), $arguments), implode("\n", $arguments) . "\n");

    }

    public function testGetUrlContent()
    {

        $testData = [

            [
                'url'        => 'http://php.net/',
                'substrings' => [
                    'class="nav"',
                    '<html',
                    '</html',
                    '<body',
                    '</body',
                    '<a href="/downloads">Downloads</a>',
                    '<a href="/mirrors.php">Mirror sites</a>',
                    'php',
                ],
            ],
            [
                'url'        => 'https://github.com/',
                'substrings' => [
                    'github',
                    'href',
                    '<svg aria-hidden="true"',
                ],
            ],

        ];

        foreach ($testData as $test) {
            $content = CasperJS::getUrlContent($test['url']);

            foreach ($test['substrings'] as $substring) {
                $this->assertContains($substring, $content);
            }

        }

    }

    public function testExecuteTraverseScript()
    {
        echo CasperJS::executeScript(static::getPackagePath('/tests/files/traverse.js'));
    }

}