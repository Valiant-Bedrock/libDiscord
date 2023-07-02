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

final class Thumbnail implements JsonSerializable {
	public function __construct(
		public string $url,
		public ?string $proxyUrl = null,
		public ?int $height = null,
		public ?int $width = null,
	) {
	}

	/**
	 * @return array{url: string, proxy_url: ?string, height: ?int, width: ?int}
	 */
	public function jsonSerialize(): array {
		return [
			"url" => $this->url,
			"proxy_url" => $this->proxyUrl,
			"height" => $this->height,
			"width" => $this->width,
		];
	}
}