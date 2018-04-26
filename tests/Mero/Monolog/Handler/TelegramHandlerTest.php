<?php

namespace Mero\Monolog\Handler;

use Mero\Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\FormatterInterface;
use Monolog\Logger;

/**
 * @author Rafael Mello <merorafael@gmail.com>
 *
 * @see    https://core.telegram.org/bots/api
 */
class TelegramHandlerTest extends TestCase
{
    /**
     * @var resource
     */
    private $res;

    /**
     * @var TelegramHandler
     */
    private $handler;

    public function setUp()
    {
        if (!extension_loaded('curl')) {
            $this->markTestSkipped('This test requires curl to run');
        }
        $this->handler = new TelegramHandler('myToken', 'myChat', Logger::DEBUG, true);
    }

    public function testCreateHandler()
    {
        $this->assertInstanceOf(TelegramHandler::class, $this->handler);
    }

    public function testWriteHeader()
    {
        $class = new \ReflectionClass(TelegramHandler::class);
        $method = $class->getMethod('buildHeader');
        $method->setAccessible(true);

        $header = $method->invoke($this->handler, 'test');

        $this->assertContains('Content-Type: application/json', $header);
        $this->assertContains('Content-Length: 4', $header);
    }

    public function testWriteContent()
    {
        $this->handler->setFormatter($this->getIdentityFormatter());

        $class = new \ReflectionClass(TelegramHandler::class);
        $method = $class->getMethod('buildContent');
        $method->setAccessible(true);

        $content = $method->invoke($this->handler, ['formatted' => 'test1']);

        $this->assertRegexp('/{"chat_id":"myChat","text":"test1"}$/', $content);
    }

    public function testWriteContentWithTelegramFormatter()
    {
        $this->handler->setFormatter(new HtmlFormatter());

        $class = new \ReflectionClass(TelegramHandler::class);
        $method = $class->getMethod('buildContent');
        $method->setAccessible(true);

        $content = $method->invoke($this->handler, ['formatted' => 'test1']);

        $this->assertRegexp('/{"chat_id":"myChat","text":"test1","parse_mode":"HTML"}$/', $content);
    }
}
