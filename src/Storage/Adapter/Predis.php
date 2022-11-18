<?php
declare(strict_types=1);

namespace Skar\Cache\Storage\Adapter;

use Predis\Client;

/**
 * Class Predis
 *
 * @package Skar\Cache
 */
final class Predis implements AdapterInterface {
	/**
	 * @var Client
	 */
	protected Client $client;

	protected string $keyTemplate = 'cache_%s';

	/**
	 * Predis constructor.
	 *
	 * @param Client $client
	 */
	public function __construct(Client $client) {
		$this->client = $client;
	}

	/**
	 * @param string $template
	 *
	 * @return static
	 */
	public function setKeyTemplate(string $template) {
		$this->keyTemplate = $template;

		return $this;
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	public function getKeyName(string $key): string {
		return sprintf($this->keyTemplate, $key);
	}

	/**
	 * @inheritdoc
	 */
	public function validateKey($key): bool {
		return is_string($key);
	}

	/**
	 * @inheritdoc
	 */
	public function has(array $keys): array {
		$result = [];
		foreach ($keys as $k => $key) {
			$result[$k] = !!$this->client->exists($this->getKeyName($key));
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
		$result = array_combine($keys, $this->client->mget(array_map([ $this, 'getKeyName' ], $keys)));
		foreach ($result as $key => $value) {
			if (!$value) {
				continue;
			}
			$items[$key] = unserialize($value);
		}

		return $items;
	}

	/**
	 * @inheritdoc
	 */
	public function set(string $key, $value, ?int $ttl = null): bool {
		$key = $this->getKeyName($key);
		if ($ttl) {
			$this->client->set($key, serialize($value), 'EX', $ttl);
		} else {
			$this->client->set($key, serialize($value));
		}

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function delete(array $keys): array {
		$result = [];
		foreach ($keys as $k => $key) {
			$result[$k] = !!$this->client->del($this->getKeyName($key));
		}

		return $result;
	}

	/**
	 * @inheritdoc
	 */
	public function clear(): bool {
		$keys = $this->client->keys($this->getKeyName('*'));

		return !!$this->client->del($keys);
	}
}
