<?php
declare(strict_types=1);

namespace Skar\Cache;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Exception;
use Psr\Cache\CacheItemInterface;
use Skar\Cache\Exception\InvalidArgumentException;

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
	 *
	 * @throws InvalidArgumentException
	 */
	public function expiresAt($expiration): static {
		if ($expiration !== null && !$expiration instanceof DateTimeInterface) {
			throw new InvalidArgumentException(sprintf(
				'Expiration date must implement DateTimeInterface or be null, "%s" given',
				is_object($expiration) ? get_class($expiration) : gettype($expiration)
			));
		}

		$this->expiresAt = $expiration?->getTimestamp();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws InvalidArgumentException
	 * @throws Exception
	 */
	public function expiresAfter($time): static {
		if ($time === null) {
			return $this->expiresAt(null);
		}

		if (is_int($time)) {
			$time = new DateInterval('PT' . $time . 'S');
		}

		if ($time instanceof DateInterval) {
			return $this->expiresAt((new DateTime())->add($time));
		}

		throw new InvalidArgumentException(sprintf(
			'Expiration date must be an integer, a DateInterval or null, "%s" given',
			is_object($time) ? get_class($time) : gettype($time)
		));
	}

	/**
	 * @return int
	 */
	public function getTtl(): int {
		$ttl = $this->expiresAt - time();

		return $ttl < 0 ? 0 : $ttl;
	}
}
