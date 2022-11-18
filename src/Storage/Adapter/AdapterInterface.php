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
	 * @param mixed $key
	 *
	 * @return bool
	 */
	public function validateKey(mixed $key): bool;

	/**
	 * Confirms if the cache contains specified keys.
	 *
	 * @param string[] $keys
	 *
	 * @return bool[]
	 */
	public function has(array $keys): array;

	/**
	 * Returns an array of cached values from storage.
	 *
	 * @param string[] $keys
	 *
	 * @return array
	 */
	public function get(array $keys): array;

	/**
	 * Save a value to the storage.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int|null $ttl
	 *
	 * @return bool
	 */
	public function set(string $key, mixed $value, ?int $ttl = null): bool;

	/**
	 * Removes the item from the storage.
	 *
	 * @param array $keys
	 *
	 * @return bool[]
	 */
	public function delete(array $keys): array;

	/**
	 * Deletes all items in the storage.
	 *
	 * @return bool
	 */
	public function clear(): bool;
}
