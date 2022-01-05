<?php
declare(strict_types=1);

namespace Skar\Cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Skar\Cache\Storage\Adapter\AdapterInterface;
use Skar\Cache\Exception;

/**
 * Class Cache
 *
 * @package Skar\Cache
 */
class Cache implements CacheItemPoolInterface {
	/**
	 * @var AdapterInterface
	 */
	protected $adapter;

	/**
	 * Cache constructor.
	 *
	 * @param AdapterInterface $adapter
	 */
	public function __construct(AdapterInterface $adapter) {
		$this->adapter = $adapter;
	}

	/**
	 * Returns a Cache Item representing the specified key.
	 *
	 * This method must always return a CacheItemInterface object, even in case of
	 * a cache miss. It MUST NOT return null.
	 *
	 * @param string $key
	 *   The key for which to return the corresponding Cache Item.
	 *
	 * @return CacheItemInterface
	 *   The corresponding Cache Item.
	 *
	 * @throws InvalidArgumentException
	 *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 */
	public function getItem($key): CacheItemInterface {
		if (!$this->adapter->validateKey($key)) {
			throw new Exception\InvalidArgumentException('Invalid key: ' . $key);
		}

		if (!$items = $this->adapter->fetch([$key])) {
			return new Item($key);
		}

		$value = array_shift($items);

		return new Item($key, $value, true);
	}

	/**
	 * Returns a traversable set of cache items.
	 *
	 * @param string[] $keys
	 *   An indexed array of keys of items to retrieve.
	 *
	 * @return array|\Traversable
	 *   A traversable collection of Cache Items keyed by the cache keys of
	 *   each item. A Cache item will be returned for each key, even if that
	 *   key is not found. However, if no keys are specified then an empty
	 *   traversable MUST be returned instead.
	 *
	 * @throws InvalidArgumentException
	 *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 */
	public function getItems(array $keys = []) {
		foreach ($keys as $key) {
			if (!$this->adapter->validateKey($key)) {
				throw new Exception\InvalidArgumentException('Invalid key: ' . $key);
			}
		}

		if (!$items = $this->adapter->fetch($keys)) {
			return [];
		}

		$result = [];
		foreach ($keys as $key) {
			if (isset($items[$key])) {
				$result[] = new Item($key, $items[$key], true);
			} else {
				$result[] = new Item($key);
			}
		}

		return $result;
	}

	/**
	 * Confirms if the cache contains specified cache item.
	 *
	 * Note: This method MAY avoid retrieving the cached value for performance reasons.
	 * This could result in a race condition with CacheItemInterface::get(). To avoid
	 * such situation use CacheItemInterface::isHit() instead.
	 *
	 * @param string $key
	 *   The key for which to check existence.
	 *
	 * @return bool
	 *   True if item exists in the cache, false otherwise.
	 *
	 * @throws InvalidArgumentException
	 *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 */
	public function hasItem($key): bool {
		if (!$this->adapter->validateKey($key)) {
			throw new Exception\InvalidArgumentException('Invalid key: ' . $key);
		}

		// TODO: Implement hasItem() method.

		return false;
	}

	/**
	 * Deletes all items in the pool.
	 *
	 * @return bool
	 *   True if the pool was successfully cleared. False if there was an error.
	 */
	public function clear(): bool {
		// TODO: Implement clear() method.

		return false;
	}

	/**
	 * Removes the item from the pool.
	 *
	 * @param string $key
	 *   The key to delete.
	 *
	 * @return bool
	 *   True if the item was successfully removed. False if there was an error.
	 *
	 * @throws InvalidArgumentException
	 *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 */
	public function deleteItem($key): bool {
		if (!$this->adapter->validateKey($key)) {
			throw new Exception\InvalidArgumentException('Invalid key: ' . $key);
		}

		// TODO: Implement deleteItem() method.

		return false;
	}

	/**
	 * Removes multiple items from the pool.
	 *
	 * @param string[] $keys
	 *   An array of keys that should be removed from the pool.
	 *
	 * @return bool
	 *   True if the items were successfully removed. False if there was an error.
	 *
	 * @throws InvalidArgumentException
	 *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 */
	public function deleteItems(array $keys): bool {
		foreach ($keys as $key) {
			if (!$this->adapter->validateKey($key)) {
				throw new Exception\InvalidArgumentException('Invalid key: ' . $key);
			}
		}

		// TODO: Implement deleteItems() method.

		return false;
	}

	/**
	 * Persists a cache item immediately.
	 *
	 * @param CacheItemInterface $item
	 *   The cache item to save.
	 *
	 * @return bool
	 *   True if the item was successfully persisted. False if there was an error.
	 *
	 * @throws Exception\InvalidArgumentException
	 * @throws \Exception
	 */
	public function save(CacheItemInterface $item): bool {
		if (!$item instanceof Item) {
			throw new Exception\InvalidArgumentException('Skar\\Cache\\Item type expected');
		}

		return $this->adapter->save($item->getKey(), $item->get(), $item->getTtl());
	}

	/**
	 * Sets a cache item to be persisted later.
	 *
	 * @param CacheItemInterface $item
	 *   The cache item to save.
	 *
	 * @return bool
	 *   False if the item could not be queued or if a commit was attempted and failed. True otherwise.
	 */
	public function saveDeferred(CacheItemInterface $item): bool {
		// TODO: Implement saveDeferred() method.

		return false;
	}

	/**
	 * Persists any deferred cache items.
	 *
	 * @return bool
	 *   True if all not-yet-saved items were successfully saved or there were none. False otherwise.
	 */
	public function commit(): bool {
		// TODO: Implement commit() method.

		return false;
	}
}
