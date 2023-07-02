<?php
/**
 * Copyright (C) 2020 - 2023 | Valiant Network
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */
declare(strict_types=1);

namespace libDiscord;

use Closure;
use Exception;
use JsonException;
use libDiscord\embed\RichEmbed;
use pocketmine\scheduler\AsyncPool;
use pocketmine\Server;
use pocketmine\utils\InternetRequestResult;
use function json_encode;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

class DiscordChannel {
	private const ENDPOINT_URL = "https://discord.com/api/webhooks/";

	protected AsyncPool $asyncPool;

	public function __construct(protected string $webhookId, ?AsyncPool $asyncPool = null) {
		$this->asyncPool = $asyncPool ?? Server::getInstance()->getAsyncPool();
	}

	private function asEndpoint(): string {
		return self::ENDPOINT_URL . $this->webhookId;
	}

	/**
	 * @param (Closure(InternetRequestResult): void)|null $onSuccess
	 * @param (Closure(Exception): void)|null $onFailure
	 * @throws JsonException
	 */
	public function sendMessage(string $content, ?Closure $onSuccess = null, ?Closure $onFailure = null): void {
		$this->send(new DiscordMessage($content), $onSuccess, $onFailure);
	}

	/**
	 * @param (Closure(InternetRequestResult): void)|null $onSuccess
	 * @param (Closure(Exception): void)|null $onFailure
	 * @throws JsonException
	 */
	public function sendEmbed(RichEmbed $embed, ?Closure $onSuccess = null, ?Closure $onFailure = null): void {
		$this->send(new DiscordMessage("", embeds: [$embed]), $onSuccess, $onFailure);
	}

	/**
	 * @param (Closure(InternetRequestResult): void)|null $onSuccess
	 * @param (Closure(Exception): void)|null $onFailure
	 * @throws JsonException
	 */
	public function send(DiscordMessage $message, ?Closure $onSuccess = null, ?Closure $onFailure = null, int $timeout = 10): void {
		$this->asyncPool->submitTask(new DiscordPostTask(
			url: $this->asEndpoint(),
			payload: json_encode($message, flags: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: throw new JsonException("Failed to encode payload"),
			onSuccess: $onSuccess,
			onFailure: $onFailure,
			timeout: $timeout,
		));
	}
}