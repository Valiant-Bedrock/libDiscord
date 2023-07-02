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

use InvalidArgumentException;
use JsonSerializable;
use libDiscord\embed\RichEmbed;
use libDiscord\mentions\AllowedMentions;
use function count;
use function strlen;

class DiscordMessage implements JsonSerializable {
	public const MAX_CONTENT_LENGTH = 2000;
	public const MAX_EMBEDS = 10;
	/**
	 * @param array<RichEmbed> $embeds
	 */
	public function __construct(
		public string $content,
		public string $username,
		public bool $tts = false,
		protected array $embeds = [],
		protected AllowedMentions $allowedMentions = new AllowedMentions([], [], [], false),
	) {
		if (strlen($this->username) <= 0 || strlen($this->username) > 80) {
			throw new InvalidArgumentException("Discord usernames can only have between 1 and 80 characters");
		}
		if (strlen($this->content) > self::MAX_CONTENT_LENGTH) {
			throw new InvalidArgumentException("Discord messages can only have up to " . self::MAX_CONTENT_LENGTH . " characters");
		}
		if (count($this->embeds) > self::MAX_EMBEDS) {
			throw new InvalidArgumentException("Discord messages can only have up to " . self::MAX_EMBEDS . " embeds");
		}
	}

	public function setContent(string $content): self {
		if (strlen($content) > self::MAX_CONTENT_LENGTH) {
			throw new InvalidArgumentException("Discord messages can only have up to " . self::MAX_CONTENT_LENGTH . " characters");
		}
		$this->content = $content;
		return $this;
	}

	/**
	 * @return array{content: string, username: string, tts: bool, embeds: array<RichEmbed>, allowed_mentions: AllowedMentions}
	 */
	public function jsonSerialize(): array {
		return [
			"content" => $this->content,
			"username" => $this->username,
			"tts" => $this->tts,
			"embeds" => $this->embeds,
			"allowed_mentions" => $this->allowedMentions
		];
	}
}