<?php
declare(strict_types=1);

namespace Skar\Cache;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Exception;
use Psr\Cache\CacheItemInterface;

/**
 * Class Item
 *
 * @package Skar\Cache
 */
class Item implements CacheItemInterface {
	protected string $key;
	protected mixed $value;
	protected bool $isHit;
	protected ?int $expiresAt;

	/**
	 * Item constructor.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param bool $isHit
	 */
	public function __construct(string $key, mixed $value = null, bool $isHit = false) {
		$this->key = $key;
		$this->value = $value;
		$this->isHit = $isHit;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getKey(): string {
		return $this->key;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get(): mixed {
		return $this->value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isHit(): bool {
		return $this->isHit;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set($value): static {
		$this->value = $value;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function expiresAt(?DateTimeInterface $expiration): static {
		$this->expiresAt = $expiration?->getTimestamp();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws Exception
	 */
	public function expiresAfter(int|DateInterval|null $time): static {
		if ($time === null) {
			return $this->expiresAt(null);
		}

		if (is_int($time)) {
			$time = new DateInterval('PT' . $time . 'S');
		}

		return $this->expiresAt((new DateTime())->add($time));
	}

	/**
	 * @return int
	 */
	public function getTtl(): int {
		$ttl = $this->expiresAt - time();

		return $ttl < 0 ? 0 : $ttl;
	}
}
