<?php

namespace protect;

use pocketmine\math\Vector3;

class cooltime{
	/**
	 * @phpstan-var array<string, self>
	 * @var self[]
	 */
	public static $players = [];

	/**
	 * @phpstan-var array<string|int, float>
	 * @var float[]
	 */
	public $items = [];

	public function __construct(){

	}

	public static function get(string $name): self{
		if(!isset(self::$players[$name])){
			self::$players[$name] = new self();
		}
		return self::$players[$name];
	}

	/**
	 * @param string|int $itemId
	 * @param float $period
	 * @return bool
	 */
	public function onuse($itemId,float $period = 1.0): bool{
		$time = microtime(true);
		if(isset($this->items[$itemId])&&$time < ($this->items[$itemId]+$period)){
			return false;
		}
		$this->items[$itemId] = $time;
		return true;
	}
}