<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-tooling for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-tooling/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Expressive\Tooling;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Zend\Expressive\Tooling\ExpressiveHelp;
use Zend\Stdlib\ConsoleHelper;

class ExpressiveHelpTest extends TestCase
{
    public function testWritesHelpMessageToConsoleUsingCommandProvidedAtInstantiationAndResourceAtInvocation()
    {
        $resource = fopen('php://temp', 'wb+');

        $console = $this->prophesize(ConsoleHelper::class);
        $console
            ->writeLine(
                Argument::that(function ($message) {
                    return false !== strpos($message, 'create-middleware');
                }),
                true,
                $resource
            )
            ->shouldBeCalled();

        $command = new ExpressiveHelp(
            'expressive',
            $console->reveal()
        );

        $this->assertNull($command($resource));
    }

    public function testTruncatesCommandToBasenameIfItIsARealpath()
    {
        $resource = fopen('php://temp', 'wb+');

        $console = $this->prophesize(ConsoleHelper::class);
        $console
            ->writeLine(
                Argument::that(function ($message) {
                    return false !== strpos($message, basename(__FILE__));
                }),
                true,
                $resource
            )
            ->shouldBeCalled();

        $command = new ExpressiveHelp(
            realpath(__FILE__),
            $console->reveal()
        );

        $this->assertNull($command($resource));
    }
}
