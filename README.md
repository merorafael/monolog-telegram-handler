TelegramHandler
===============

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d7f41933-3e48-4c2d-befc-35aba76bf0ef/mini.png)](https://insight.sensiolabs.com/projects/d7f41933-3e48-4c2d-befc-35aba76bf0ef)
[![Build Status](https://travis-ci.org/merorafael/telegram-handler.svg?branch=master)](https://travis-ci.org/merorafael/telegram-handler)
[![Coverage Status](https://coveralls.io/repos/github/merorafael/telegram-handler/badge.svg?branch=master)](https://coveralls.io/github/merorafael/telegram-handler?branch=master)
[![Latest Stable Version](https://poser.pugx.org/mero/telegram-handler/v/stable.svg)](https://packagist.org/packages/mero/telegram-handler) 
[![Total Downloads](https://poser.pugx.org/mero/telegram-handler/downloads.svg)](https://packagist.org/packages/mero/telegram-handler) 
[![License](https://poser.pugx.org/mero/telegram-handler/license.svg)](https://packagist.org/packages/mero/telegram-handler)

Monolog handler to send log by Telegram.

Requirements
------------

- PHP 5.6 or above

Instalation with composer
-------------------------

1. Open your project directory;
2. Run `composer require mero/telegram-handler` to add `TelegramHandler` in your project vendor.

Declaring handler object
------------------------

To declare this handler, you need to know the bot token and the chat identifier(chat_id) to
which the log will be sent.

```php
// ...
$handler = new \Mero\Monolog\Handler\TelegramHandler('<token>', <chat_id>, <log_level>);
// ...
```

**Example:**

```php
<?php

$log = new \Monolog\Logger('telegram_channel');

$handler = new \Mero\Monolog\Handler\TelegramHandler(
    '000000000:XXXXX-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
    111111111,
    \Monolog\Logger::DEBUG
);
$handler->setFormatter(new \Monolog\Formatter\LineFormatter());
$log->pushHandler($handler);

$log->debug('Message log');
```

Creating a bot
--------------

To use this handler, you need to create your bot on telegram and receive the Bot API access token.
To do this, start a conversation with **@BotFather**.

**Conversation example:**

In the example below, I'm talking to **@BotFather**. to create a bot named "Cronus Bot" with user "@cronus_bot".

```
Me: /newbot
---
@BotFather: Alright, a new bot. How are we going to call it? Please choose a name for your bot.
---
Me: Cronus Bot
---
@BotFather: Good. Now let's choose a username for your bot. It must end in `bot`. Like this, for example: 
TetrisBot or tetris_bot.
---
Me: cronus_bot
---
@BotFather: Done! Congratulations on your new bot. You will find it at telegram.me/cronus_bot. You can now add a 
description, about section and profile picture for your bot, see /help for a list of commands. By the way, when 
you've finished creating your cool bot, ping our Bot Support if you want a better username for it. Just make sure 
the bot is fully operational before you do this.

Use this token to access the HTTP API:
000000000:XXXXX-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx

For a description of the Bot API, see this page: https://core.telegram.org/bots/api
```

Give a chat identifier
----------------------

To retrieve the chat_id in which the log will be sent, the recipient user will first need a conversation with 
the bot. After the conversation has started, make the request below to know the chat_id of that conversation.

**URL:** https://api.telegram.org/bot_token_/getUpdates

**Example:**

```
Request
-------
POST https://api.telegram.org/bot000000000:XXXXX-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx/getUpdates

Response
--------
{
  "ok": true,
  "result": [
    {
      "update_id": 141444845,
      "message": {
        "message_id": 111,
        "from": {
          "id": 111111111,
          "first_name": "Rafael",
          "last_name": "Mello",
          "username": "merorafael"
        },
        "chat": {
          "id": 111111111,
          "first_name": "Rafael",
          "last_name": "Mello",
          "username": "merorafael",
          "type": "private"
        },
        "date": 1480701504,
        "text": "test"
      }
    }
  ]
}
```

In the above request, the chat_id is represented by the number "111111111".
