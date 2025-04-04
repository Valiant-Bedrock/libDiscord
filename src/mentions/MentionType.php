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

namespace libDiscord\mentions;

use JsonSerializable;

enum MentionType: string implements JsonSerializable {
	case EVERYONE = "everyone";
	case USERS = "users";
	case ROLES = "roles";

	public function jsonSerialize(): string {
		return $this->value;
	}
}