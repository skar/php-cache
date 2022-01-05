<?php
declare(strict_types=1);

namespace Skar\Cache\Storage\Adapter;

/**
 * Class Memory
 *
 * @package Skar\Cache
 */
class Memory implements AdapterInterface {
	/**
	 * @var array<string, array{int|null, string}>
	 */
	protected array $data = [];

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function validateKey(string $key): bool {
		return true;
	}

	/**
	 * @param string[] $keys
	 *
	 * @return array
	 */
	public function fetch(array $keys): array {
		if (!$keys) {
			return [];
		}

		$items = [];
		$result = array_intersect_key($keys, $this->data);
		foreach ($result as $key => $data) {
			if ($data[0] && $data[0] < time()) {
				unset($this->data[$key]);
			}
			$items[$key] = $data[1];
		}

		return $items;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int|null $ttl
	 *
	 * @return bool
	 */
	public function save(string $key, $value, ?int $ttl = null): bool {
		$this->data[$key] = [
			$ttl ? time() + $ttl : null,
			$value,
		];
		return true;
	}
}
