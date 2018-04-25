<?php

namespace Mero\Monolog\Handler;

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
     * @param string $token  Telegram API token
     * @param int    $chatId Chat identifier
     * @param int    $level  The minimum logging level at which this handler will be triggered
     * @param bool   $bubble Whether the messages that are handled can bubble up the stack or not
     *
     * @throws MissingExtensionException If the PHP cURL extension is not loaded
     */
    public function __construct($token, $chatId, $level = Logger::CRITICAL, $bubble = true)
    {
        if (!extension_loaded('curl')) {
            throw new MissingExtensionException('The cURL PHP extension is required to use the TelegramHandler');
        }

        $this->token = $token;
        $this->chatId = $chatId;

        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritdoc}
     *
     * @param array $record
     */
    protected function write(array $record)
    {
        $postData = json_encode([
            'chat_id' => $this->chatId,
            'text' => $record['formatted'],
        ]);

        $telegramUrl = sprintf(
            'https://api.telegram.org/bot%s/sendMessage',
            $this->token
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: '.strlen($postData)
        ]);
        curl_setopt($ch, CURLOPT_URL, $telegramUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        Curl\Util::execute($ch);
    }
}
