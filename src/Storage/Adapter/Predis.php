<?php
namespace Skar\Cache\Storage\Adapter;

use Predis\Client;

/**
 * Class Predis
 *
 * @package Skar\Cache
 */
class Predis implements AdapterInterface {
	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * Predis constructor.
	 *
	 * @param Client $client
	 */
	public function __construct(Client $client) {
		$this->client = $client;
	}

	/**
	 * Validate key for storage
	 *
	 * @param $key
	 *
	 * @return bool
	 */
	public function validateKey($key): bool {
		return true;
	}

	/**
	 * @param $keys
	 *
	 * @return array
	 */
	public function fetch(array $keys): array {
		if (!$keys) {
			return [];
		}

		$items = [];

		$result = array_combine($keys, $this->client->mget($keys));

		foreach ($result as $key => $value) {
			if ($value) {
				$items[$key] = unserialize($value);
			}
		}

		return $items;
	}

	/**
	 * @param $key
	 * @param $value
	 * @param $ttl
	 *
	 * @return bool
	 */
	public function save($key, $value, $ttl = null): bool {
		if ($ttl) {
			$this->client->set($key, serialize($value), 'EX', $ttl);
		} else {
			$this->client->set($key, serialize($value));
		}

		return true;
	}
}
