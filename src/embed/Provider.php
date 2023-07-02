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

final class Provider implements JsonSerializable {

	public function __construct(
		public ?string $name = null,
		public ?string $url = null,
	) {
	}

	/**
	 * @return array{name: ?string, url: ?string}
	 */
	public function jsonSerialize(): array {
		return [
			"name" => $this->name,
			"url" => $this->url,
		];
	}
}