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
use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;
use pocketmine\utils\InternetException;
use pocketmine\utils\InternetRequestResult;
use RuntimeException;
use function igbinary_serialize;
use function igbinary_unserialize;

final class DiscordPostTask extends AsyncTask {
	private string $headers;

	/**
	 * @param Closure(InternetRequestResult): void|null $onSuccess
	 * @param Closure(Exception): void|null $onFailure
	 * @param string[]|null $headers
	 */
	public function __construct(
		public readonly string $url,
		public readonly string $payload,
		public readonly ?Closure $onSuccess = null,
		public readonly ?Closure $onFailure = null,
		public readonly int $timeout = 10,
		?array $headers = null,
	) {
		$this->headers = igbinary_serialize($headers ?? []) ?? throw new RuntimeException("Failed to serialize headers");
	}

	public function onRun(): void {
		/** @var string[] $headers */
		$headers = igbinary_unserialize($this->headers);
		/** @var string|null $error */
		$error = null;
		$data = Internet::postURL(
			page: $this->url,
			args: $this->payload,
			timeout: $this->timeout,
			extraHeaders: [
				...$headers,
				"Content-Type: application/json",
			],
			err: $error
		);
		if ($data instanceof InternetRequestResult) {
			$success = $data->getCode() >= 200 && $data->getCode() < 300;
			$this->setResult($success ? $data : new InternetException("HTTP error: {$data->getCode()}: {$data->getBody()}"));
		} else {
			$this->setResult(new Exception($error ?? "Unknown error"));
		}
	}

	public function onCompletion(): void {
		$result = $this->getResult();
		if ($result instanceof InternetRequestResult && $this->onSuccess !== null) {
			($this->onSuccess)($result);
		} else if ($result instanceof Exception && $this->onFailure !== null) {
			($this->onFailure)($result);
		}
	}
}