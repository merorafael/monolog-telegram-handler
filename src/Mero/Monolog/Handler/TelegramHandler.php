<?php

namespace Mero\Monolog\Handler;

use Mero\Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Handler\Curl;
use Monolog\Logger;

/**
 * Sends notifications through Telegram API.
 *
 * @author Rafael Mello <merorafael@gmail.com>
 *
 * @see    https://core.telegram.org/bots/api
 */
class TelegramHandler extends AbstractProcessingHandler
{
    /**
     * @var string Telegram API token
     */
    private $token;

    /**
     * @var int Chat identifier
     */
    private $chatId;

    /**
     * @var int Request timeout
     */
    private $timeout;

    /**
     * @param string $token  Telegram API token
     * @param int    $chatId Chat identifier
     * @param int    $level  The minimum logging level at which this handler will be triggered
     * @param bool   $bubble Whether the messages that are handled can bubble up the stack or not
     *
     * @throws MissingExtensionException If the PHP cURL extension is not loaded
     */
    public function __construct(
        $token,
        $chatId,
        $level = Logger::CRITICAL,
        $bubble = true
    ) {
        if (!extension_loaded('curl')) {
            throw new MissingExtensionException('The cURL PHP extension is required to use the TelegramHandler');
        }

        $this->token = $token;
        $this->chatId = $chatId;
        $this->timeout = 0;

        parent::__construct($level, $bubble);
    }

    /**
     * Define a timeout to Telegram send message request.
     *
     * @param int $timeout Request timeout
     *
     * @return TelegramHandler
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Builds the header of the API Call.
     *
     * @param string $content
     *
     * @return array
     */
    protected function buildHeader($content)
    {
        return [
            'Content-Type: application/json',
            'Content-Length: '.strlen($content),
        ];
    }

    /**
     * Builds the body of API call.
     *
     * @param array $record
     *
     * @return string
     */
    protected function buildContent(array $record)
    {
        $content = [
            'chat_id' => $this->chatId,
            'text' => $record['formatted'],
        ];

        if ($this->formatter instanceof HtmlFormatter) {
            $content['parse_mode'] = 'HTML';
        }

        return json_encode($content);
    }

    /**
     * {@inheritdoc}
     *
     * @param array $record
     */
    protected function write(array $record)
    {
        $content = $this->buildContent($record);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->buildHeader($content));
        curl_setopt($ch, CURLOPT_URL, sprintf('https://api.telegram.org/bot%s/sendMessage', $this->token));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        Curl\Util::execute($ch);
    }
}
