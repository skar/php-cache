<?php
declare(strict_types=1);

namespace Skar\Cache\Storage\Adapter;

/**
 * Class Memory
 *
 * @package Skar\Cache
 */
final class Memory implements AdapterInterface {
	/**
	 * @var array<string, mixed>
	 */
	private array $data = [];

	/**
	 * @var array<string, int>
	 */
	private array $expired = [];

	/**
	 * @inheritdoc
	 */
	public function validateKey(mixed $key): bool {
		return is_string($key);
	}

	/**
	 * @inheritdoc
	 */
	public function has(array $keys): array {
		$this->checkExpired($keys);

		$result = [];
		foreach ($keys as $k => $key) {
			$result[$k] = array_key_exists($key, $this->data);
		}
		return $result;
	}

	/**
	 * @inheritdoc
	 */
	public function get(array $keys): array {
		if (!$keys) {
			return [];
		}

		$items = [];
		$result = array_intersect_key($keys, $this->data);
		$this->checkExpired(array_keys($result));
		foreach ($result as $key => $data) {
			$items[$key] = $data[1];
		}

		return $items;
	}

	/**
	 * @inheritdoc
	 */
	public function set(string $key, mixed $value, ?int $ttl = null): bool {
		$this->data[$key] = $value;

		if ($ttl) {
			$this->expired[$key] = time() + $ttl;
		}

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function delete(array $keys): array {
		$result = [];
		foreach ($keys as $k => $key) {
			if (!array_key_exists($key, $this->data)) {
				$result[$k] = false;
				continue;
			}

			unset($this->data[$key]);

			if (array_key_exists($key, $this->expired)) {
				unset($this->expired[$key]);
			}
			$result[$k] = true;
		}

		return $result;
	}

	/**
	 * @inheritdoc
	 */
	public function clear(): bool {
		$this->data = [];
		return true;
	}

	/**
	 * @param array $keys
	 *
	 * @return void
	 */
	private function checkExpired(array $keys = []): void {
		if (count($keys) === 0) {
			$keys = array_keys($this->data);
		}

		$time = time();
		foreach ($keys as $key) {
			if (!array_key_exists($key, $this->expired)) {
				continue;
			}

			if ($this->expired[$key] > $time) {
				continue;
			}

			$this->delete([$key]);
		}
	}
}
