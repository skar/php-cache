<?php
namespace Skar\Cache\Exception;

use Exception;
use \Psr\Cache\InvalidArgumentException as InvalidArgumentExceptionInterface;

/**
 * Class InvalidArgumentException
 *
 * @package Skar\Cache
 */
class InvalidArgumentException extends Exception implements InvalidArgumentExceptionInterface {
}
