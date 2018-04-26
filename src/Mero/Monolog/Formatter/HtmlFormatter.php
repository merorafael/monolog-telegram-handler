<?php

namespace Mero\Monolog\Formatter;

use Monolog\Formatter\NormalizerFormatter;
use Symfony\Component\Yaml\Yaml;

/**
 * Telegram formatting support using HTML.
 *
 * @author Rafael Mello <merorafael@gmail.com>
 *
 * @see    https://core.telegram.org/bots/api#formatting-options
 */
class HtmlFormatter extends NormalizerFormatter
{
    /**
     * @inheritDoc
     */
    public function __construct($dateFormat = null)
    {
        parent::__construct($dateFormat);
    }

    /**
     * Formats a log record.
     *
     * @param  array $record A record to format
     * @return mixed The formatted record
     */
    public function format(array $record)
    {
        $output = "<b>{$record['level_name']}</b>".PHP_EOL;
        $output .= "<b>Message:</b> {$record['message']}".PHP_EOL;
        $output .= "<b>Time:</b> {$record['datetime']->format($this->dateFormat)}".PHP_EOL;
        $output .= "<b>Channel:</b> {$record['channel']}".PHP_EOL;

        if ($record['context']) {
            $output .= PHP_EOL;
            $output .= "[context]".PHP_EOL;
            $output .= Yaml::dump($record['context']);
        }
        if ($record['extra']) {
            $output .= PHP_EOL;
            $output .= "[context]".PHP_EOL;
            $output .= Yaml::dump($record['extra']);
        }

        return $output;
    }

    /**
     * Formats a set of log records.
     *
     * @param  array $records A set of records to format
     * @return mixed The formatted set of records
     */
    public function formatBatch(array $records)
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }
}
