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

namespace libDiscord\embed;

use JsonSerializable;

final class Field implements JsonSerializable {

	public function __construct(
		public string $name,
		public string $value,
		public bool $inline = false,
	) {
	}

	/**
	 * @return array{name: string, value: string, inline: bool}
	 */
	public function jsonSerialize(): array {
		return [
			"name" => $this->name,
			"value" => $this->value,
			"inline" => $this->inline,
		];
	}
}