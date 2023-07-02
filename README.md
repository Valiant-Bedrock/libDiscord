# libDiscord
A small PocketMine-MP library to connect to Discord's webhook integration.

## Installation
The recommended way to install this library is through [Composer](https://getcomposer.org/).
```bash
composer require valiant-bedrock/libdiscord
```

## Usage
```php
<?php
// by default, the library uses PocketMine's built-in AsyncPool but one can be specified using the `asyncPool` parameter
$channel = new \libDiscord\DiscordChannel(webhookId: "example-webhook-id");
// send using `DiscordMessage` object
$channel->send(new \libDiscord\DiscordMessage("Hello, world!"));
// send using `string` message
$channel->sendMessage("Hello, world!");
// send embed
$channel->sendEmbed(new \libDiscord\DiscordEmbed(title: "Hello, world!"));
```