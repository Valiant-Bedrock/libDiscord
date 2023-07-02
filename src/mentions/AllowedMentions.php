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

use Closure;
use InvalidArgumentException;
use JsonSerializable;
use function preg_match;

final class AllowedMentions implements JsonSerializable {
	/**
	 * @param array<MentionType> $types
	 * @param array<string> $users
	 * @param array<string> $roles
	 */
	public function __construct(private array $types, private array $users, private array $roles, private bool $repliedUser) {
		self::validateAll("type", $types, fn (mixed $type) => $type instanceof MentionType);
		self::validateAll("user", $users, fn (string $user) => preg_match("/<@!?[0-9]+>/", $user) === 1);
		self::validateAll("role", $roles, fn (string $role) => preg_match("/<@&[0-9]+>/", $role) === 1);
	}

	public function addMention(MentionType $type): self {
		self::validate("type", $type, fn (MentionType $type) => MentionType::tryFrom($type->value) !== null);
		$this->types[] = $type;
		return $this;
	}

	public function addUserById(string $id): self {
		self::validate("user", $id, fn (string $id) => preg_match("/[0-9]+/", $id) === 1);
		$this->users[] = "<@$id>";
		return $this;
	}

	public function addUser(string $user): self {
		self::validate("user", $user, fn (string $user) => preg_match("/<@!?[0-9]+>/", $user) === 1);
		$this->users[] = $user;
		return $this;
	}

	public function addRoleById(string $id): self {
		self::validate("role", $id, fn (string $id) => preg_match("/[0-9]+/", $id) === 1);
		$this->roles[] = "<@&$id>";
		return $this;
	}

	public function addRole(string $role): self {
		self::validate("role", $role, fn (string $role) => preg_match("/<@&[0-9]+>/", $role) === 1);
		$this->roles[] = $role;
		return $this;
	}

	public function setRepliedUser(bool $repliedUser): self {
		$this->repliedUser = $repliedUser;
		return $this;
	}

	/**
	 * @return array{parse: array<MentionType>, users: array<string>, roles: array<string>, replied_user: bool}
	 */
	public function jsonSerialize(): array {
		return [
			"parse" => $this->types,
			"users" => $this->users,
			"roles" => $this->roles,
			"replied_user" => $this->repliedUser
		];
	}

	/**
	 * @template TValue
	 * @param array<TValue> $values
	 * @param Closure(TValue): bool $validator
	 */
	private static function validateAll(string $label, mixed $values, Closure $validator): void {
		foreach ($values as $value) {
			self::validate($label, $value, $validator);
		}
	}

	/**
	 * @template TValue
	 * @param TValue $value
	 * @param Closure(TValue): bool $validator
	 */
	private static function validate(string $label, mixed $value, Closure $validator): void {
		if (!$validator($value)) {
			throw new InvalidArgumentException("Invalid $label: $value");
		}
	}
}