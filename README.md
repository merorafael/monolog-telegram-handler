TelegramHandler
===============

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d7f41933-3e48-4c2d-befc-35aba76bf0ef/mini.png)](https://insight.sensiolabs.com/projects/d7f41933-3e48-4c2d-befc-35aba76bf0ef)
[![Build Status](https://travis-ci.org/merorafael/telegram-handler.svg?branch=master)](https://travis-ci.org/merorafael/telegram-handler)
[![Latest Stable Version](https://poser.pugx.org/mero/telegram-handler/v/stable.svg)](https://packagist.org/packages/mero/telegram-handler) 
[![Total Downloads](https://poser.pugx.org/mero/telegram-handler/downloads.svg)](https://packagist.org/packages/mero/telegram-handler) 
[![License](https://poser.pugx.org/mero/telegram-handler/license.svg)](https://packagist.org/packages/mero/telegram-handler)

Monolog handler to send log by Telegram. **[This project is in the approval stage to be implemented officially in Monolog](https://github.com/Seldaek/monolog/pull/869)**.

Requirements
------------

- PHP 5.4 or above
- Yii 2.0.0 or above

Instalation with composer
-------------------------

1. Open your project directory;
2. Run `composer require mero/telegram-handler` to add `TelegramHandler` in your project vendor.

Declaring handler object
------------------------

```php
// ...

$handler = new \Mero\Monolog\Handler\TelegramHandler(
    'TOKEN',
    'CHAT_ID',
    Logger::CRITICAL
);

// ...
```
