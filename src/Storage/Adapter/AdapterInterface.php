<?php
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
	 * @param $key
	 *
	 * @return bool
	 */
	public function validateKey($key): bool;

	/**
	 * @param $keys
	 *
	 * @return array
	 */
	public function fetch(array $keys): array;

	/**
	 * @param $key
	 * @param $value
	 * @param $ttl
	 *
	 * @return bool
	 */
	public function save($key, $value, $ttl = null): bool;
}
