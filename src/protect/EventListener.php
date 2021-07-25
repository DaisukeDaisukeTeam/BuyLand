<?php


namespace protect;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class EventListener implements Listener{
	public PluginBase $plugin;
	public Protect $protect;

	public function __construct(PluginBase $plugin){
		$this->plugin = $plugin;
		$this->protect = Protect::getInstance();
	}

	public function onPlayerInteract(PlayerInteractEvent $event) : void{
		if($this->CheckProtecte($event->getPlayer(), $event->getBlock()->getSide($event->getFace()))){
			$event->setCancelled();
		}
	}

	public function on1(BlockBreakEvent $event) : void{
		if($this->CheckProtecte($event->getPlayer(), $event->getBlock())){
			$event->setCancelled();
		}
	}

	public function a(EntityExplodeEvent $event) : void{
		if($this->CheckProtecte(null, $event->getPosition(), 15)){
			$event->setCancelled();
		}
	}

	/**
	 * @param Player|null $player
	 * @param Vector3|Position $vector3
	 * @param int $expand
	 * @return bool
	 */
	public function CheckProtecte(?Player $player, Vector3 $vector3, int $expand = 0) : bool{
		$level = null;
		if($player instanceof Player){
			$level = $player->getLevel();
		}elseif($vector3 instanceof Position){
			$level = $vector3->getLevel();
		}

		if($level === null){
			throw new \LogicException("\$level === null");
		}

		return $this->protect->isProtected($player, $level, $vector3, $expand) === Protect::TYPE_DONT_HAVE_PERMISSION;
	}

	public function getProtect() : Protect{
		return $this->protect;
	}

	protected function getPlugin() : PluginBase{
		return $this->plugin;
	}
}