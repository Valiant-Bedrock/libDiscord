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

use DateTime;
use JsonSerializable;
use pocketmine\color\Color;
use function hexdec;
use function is_int;
use function is_string;

final class RichEmbed implements JsonSerializable {
	/**
	 * @param array<Field> $fields
	 */
	public function __construct(
		public string $title,
		public string $description,
		public ?string $url = null,
		public ?DateTime $timestamp = null,
		public Color|string|int|null $color = null,
		public ?Footer $footer = null,
		public ?Image $image = null,
		public ?Thumbnail $thumbnail = null,
		public ?Video $video = null,
		public ?Provider $provider = null,
		public ?Author $author = null,
		public array $fields = [],
	) {
	}

	public function setTitle(string $title): self {
		$this->title = $title;
		return $this;
	}

	public function setDescription(string $description): self {
		$this->description = $description;
		return $this;
	}

	public function setUrl(?string $url): self {
		$this->url = $url;
		return $this;
	}

	public function setTimestamp(?DateTime $timestamp): self {
		$this->timestamp = $timestamp;
		return $this;
	}

	public function setColor(Color|string|int|null $color): self {
		$this->color = $color;
		return $this;
	}

	public function setFooter(?Footer $footer): self {
		$this->footer = $footer;
		return $this;
	}

	public function setImage(?Image $image): self {
		$this->image = $image;
		return $this;
	}

	public function setThumbnail(?Thumbnail $thumbnail): self {
		$this->thumbnail = $thumbnail;
		return $this;
	}

	public function setVideo(?Video $video): self {
		$this->video = $video;
		return $this;
	}

	public function setProvider(?Provider $provider): self {
		$this->provider = $provider;
		return $this;
	}

	public function setAuthor(?Author $author): self {
		$this->author = $author;
		return $this;
	}

	public function addField(Field $field): self {
		$this->fields[] = $field;
		return $this;
	}

	/**
	 * @return array{title: string, description: string, url: ?string, timestamp: ?string, color: ?int, footer: ?Footer, image: ?Image, thumbnail: ?Thumbnail, video: ?Video, provider: ?Provider, author: ?Author, fields: array<Field>}
	 */
	public function jsonSerialize(): array {
		return [
			"title" => $this->title,
			"description" => $this->description,
			"url" => $this->url,
			"timestamp" => $this->timestamp?->format(DateTime::ISO8601),
			"color" => match (true) {
				$this->color instanceof Color => $this->color->toARGB(),
				is_string($this->color) => (int) hexdec($this->color),
				is_int($this->color) => $this->color,
				default => null,
			},
			"footer" => $this->footer,
			"image" => $this->image,
			"thumbnail" => $this->thumbnail,
			"video" => $this->video,
			"provider" => $this->provider,
			"author" => $this->author,
			"fields" => $this->fields,
		];
	}
}