<?php

namespace protect;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use pjz9n\libi18n\LangHolder;

class RangeEventListener implements Listener{
	public int $itemId;
	public int $ItemDamage;
	public ?string $itemCustomName;

	public function __construct(Config $config){
		$this->itemId = (int) $config->get("ItemId", 284);
		$this->ItemDamage = (int) $config->get("ItemDamage", 0);
		$this->itemCustomName = (string) $config->get("Item_CustomName", null);
		$Item = (Item::get($this->itemId, $this->ItemDamage))->setCustomName($this->itemCustomName);
		Item::addCreativeItem($Item);
	}

	public function onPlayerInteract(PlayerInteractEvent $event) : void{
		if(
			$event->getItem()->getId() === $this->itemId&&
			$event->getItem()->getDamage() === $this->ItemDamage&&
			$event->getItem()->getCustomName() === $this->itemCustomName
		){
			$event->setCancelled();
			$player = $event->getPlayer();
			$name = $player->getName();

			if($event->getBlock()->x === 0&&$event->getBlock()->y === 0&&$event->getBlock()->z === 0){
				return;
			}

			if(!cooltime::get($name)->onuse("on", 0.5)){
				return;
			}

			$range = RangeLevel::get($name);

			$lang = LangHolder::get();

			if($player->isSneaking()){
				$range->unsetPos();
				$player->sendMessage($lang->translate("pos.reset"));
				return;
			}

			$range->setLevel($player->getLevelNonNull()->getName());

			$pos = $event->getBlock()->asVector3();
			$array = ["x" => (string) $pos->x, "y" => (string) $pos->y, "z" => (string) $pos->z];
			if(!$range->hasPos(0)){
				$range->setPos(0, $pos);
				$player->sendMessage($lang->translate("pos.pos1", $array));
				if($range->isCompleted()){
					[$sx, $sy, $sz, $ex, $ey, $ez] = $range->getRangePos();
					$array1 = ["sx" => (string) $sx, "sy" => (string) $sy, "sz" => (string) $sz, "ex" => (string) $ex, "ey" => (string) $ey, "ez" => (string) $ez, "count" => (string) $range->CountBlocks(), "price" => (string) $range->calculatePrice()];
					$player->sendMessage($lang->translate("pos.complete", $array1));

				}
			}else if(!$range->hasPos(1)){
				$range->setPos(1, $pos);
				$player->sendMessage($lang->translate("pos.pos2", $array));
				if($range->isCompleted()){
					[$sx, $sy, $sz, $ex, $ey, $ez] = $range->getRangePos();
					$array1 = ["sx" => (string) $sx, "sy" => (string) $sy, "sz" => (string) $sz, "ex" => (string) $ex, "ey" => (string) $ey, "ez" => (string) $ez, "count" => (string) $range->CountBlocks(), "price" => (string) $range->calculatePrice()];
					$player->sendMessage($lang->translate("pos.complete", $array1));
				}
			}
		}
	}
}
