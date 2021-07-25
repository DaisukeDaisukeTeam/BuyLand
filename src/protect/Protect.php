<?php

namespace protect;

use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginLogger;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class Protect{
	public const TYPE_HAVE_PERMISSION = 0;
	public const TYPE_DONT_HAVE_PERMISSION = 1;
	public const TYPE_NO_PERMISSION = 2;
	use SingletonTrait;

	public PluginLogger $logger;

	public Config $listConfig;
	public Config $permissionsConfig;


	/** @var array<string, array<int, range>> $list */
	public array $list = [];
	/** @var array<string, mixed> $list1 */
	public array $list1 = [];
	/** @var array<int, array<string, true>> $permissions */
	public array $permissions = [];

	public function __construct(PluginLogger $logger, Config $lists,Config $permissions){
		$this->listConfig = $lists;
		$this->permissionsConfig = $permissions;

		$this->logger = $logger;
		$this->permissions = $this->permissionsConfig->get("data",[]);
		$this->list1 = $this->listConfig->get("data",[]);
		$this->getListByArray($this->list1);

		self::setInstance($this);
	}

	/**
	 * @param array<string, array<int, array{0: int|float, 1: int|float, 2: int|float, 3: int|float, 4: int|float, 5: int|float}>> $list
	 */
	public function getListByArray(array $list) : void{
		foreach($list as $name => $array){
			foreach($array as $index => $item){
				$this->list[$name][$index] = range::fromRangePos($item);
			}
		}
	}

	/**
	 * @param ?Player $player
	 * @param Level $level
	 * @param Vector3 $vector3
	 * @param int $expand
	 * @return int
	 */
	public function isProtected(?Player $player,Level $level, Vector3 $vector3, int $expand = 0): int{
		/*foreach(($this->index[$hash = (Level::chunkHash($vector3->x  >> 4,$vector3->z >> 4))] ?? []) as $id){
			if(!isset($this->list[$id])){
				$this->getLogger()->warning("error: missing index. debug info: $vector3->x, $vector3->y, $vector3->z, $hash, $id");
			}
			$range = $this->list[$id];
		}*/
		foreach(($this->list[strtolower($level->getName())] ?? []) as $id => $range){
			if($range->isRangeVectorInside($vector3, $expand)){
				if(!$player instanceof Player){
					return self::TYPE_DONT_HAVE_PERMISSION;
				}

				if(isset($this->permissions[$id][strtolower($player->getName())])){
					return self::TYPE_HAVE_PERMISSION;
				}
				return self::TYPE_DONT_HAVE_PERMISSION;
			}
		}
		return self::TYPE_NO_PERMISSION;
	}

	public function addProtect(Player $player, RangeLevel $range) : void{
		$this->permissions[][$player->getName()] = true;
		$id = array_key_last($this->permissions);
		$this->list[$range->getLevelNonNull()][$id] = $range;
		$this->list1[$range->getLevelNonNull()][$id] = range::save($range);
		$this->save();
	}

	public function save() : void{
		$this->listConfig->set("data", $this->list1);
		$this->permissionsConfig->set("data", $this->permissions);

		$this->listConfig->save();
		$this->permissionsConfig->save();
	}

	/**
	 * @return PluginLogger
	 */
	public function getLogger() : PluginLogger{
		return $this->logger;
	}
}