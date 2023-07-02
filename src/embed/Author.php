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

final class Author implements JsonSerializable {

	public function __construct(
		public string $name,
		public ?string $url = null,
		public ?string $iconUrl = null,
		public ?string $proxyIconUrl = null,
	) {
	}

	/**
	 * @return array{name: string, url: ?string, icon_url: ?string, proxy_icon_url: ?string}
	 */
	public function jsonSerialize(): array {
		return [
			"name" => $this->name,
			"url" => $this->url,
			"icon_url" => $this->iconUrl,
			"proxy_icon_url" => $this->proxyIconUrl,
		];
	}
}