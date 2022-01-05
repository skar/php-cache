<?php
declare(strict_types=1);

namespace Skar\Cache\Storage\Adapter;

/**
 * Interface AdapterInterface
 *
 * @package Skar\Cache
 */
interface AdapterInterface {
	/**
	 * Validate key for storage
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function validateKey(string $key): bool;

	/**
	 * @param string[] $keys
	 *
	 * @return array
	 */
	public function fetch(array $keys): array;

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int|null $ttl
	 *
	 * @return bool
	 */
	public function save(string $key, $value, ?int $ttl = null): bool;
}
