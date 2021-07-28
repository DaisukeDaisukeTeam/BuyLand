<?php

namespace protect;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use protect\command\BuyLandCommand;
use pjz9n\libi18n\LangHolder;
use pjz9n\libi18n\LangInstance;
use protect\provider\MoneyProvider;

class Main extends PluginBase{

	public Config $setting;

	/*public function onLoad(){
		$this->setting = new Config($this->getDataFolder()."setting.yml", Config::YAML, [
			"ItemId" => 284,
			"ItemDamage" => 0,
			"Item_CustomName" => null,

			"Price Per Block" => 0.5,
			"language" => "eng",
		]);
		$this->saveResource("eng.yml");
		LangHolder::init(new LangInstance($this->setting->get("language"), "eng", $this->getDataFolder(), $this->getLogger()));
	}*/

	public function onEnable(){

		$this->getLogger()->warning("This plugin is in the process of being created and will not work.");
		$this->getLogger()->notice("I recommend using the following plugins.");
		$this->getLogger()->notice("iProtector: https://poggit.pmmp.io/p/iProtector/");
		$this->getLogger()->notice("WorldProtect: https://poggit.pmmp.io/p/WorldProtect");
		$this->getLogger()->notice("WorldGuard: https://poggit.pmmp.io/p/WorldGuard");
		$this->getLogger()->error("Disable this plugin.");

		$this->getServer()->getPluginManager()->disablePlugin($this);
		return;


		$list = new Config($this->getDataFolder()."list.yml", Config::YAML, []);
		$permissions = new Config($this->getDataFolder()."permissions.yml", Config::YAML, []);
		$this->setting = new Config($this->getDataFolder()."setting.yml", Config::YAML, [
			"ItemId" => 284,
			"ItemDamage" => 0,
			"Item_CustomName" => null,

			"Price Per Block" => 0.5,
			"language" => "eng",
		]);

		$this->saveResource("eng.yml");

		LangHolder::init(new LangInstance($this->setting->get("language","eng"), "eng", $this->getDataFolder(), $this->getLogger()));
		$lang = LangHolder::get();
		$setting = $this->setting;

		$this->saveResource("Providers.yml");
		$config = new Config($this->getDataFolder()."Providers.yml", Config::YAML);
		MoneyProvider::init($config);
		if(MoneyProvider::isEmpty()){
			$this->getLogger()->error($lang->translate("internal.moneyapi.notfound.startup"));
			$this->getLogger()->notice($lang->translate("internal.moneyapi.notfound.startup1"));
			foreach(MoneyProvider::getProviders() as $name => $class_string){
				$this->getLogger()->info($lang->translate("%internal.moneyapi.notfound.list%".$name));
			}
			//$this->getScheduler()->scheduleDelayedTask(new ConsoleReportNoticeTask($this->getLogger(), $lang->translate("internal.moneyapi.notfound.complete", ["api" => $apilist])), 10);
		}else{
			$this->getLogger()->debug($lang->translate("internal.moneyapi.set", ["name" => MoneyProvider::getName()]));
		}

		new Protect($this->getLogger(), $list, $permissions);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new RangeEventListener($setting), $this);

		/*$this->getServer()->getCommandMap()->registerAll("protect", [
			new BuyLandCommand($this, $lang->translate("command.BuyLandCommand._name"), $lang->translate("command.BuyLandCommand._description")),
		]);*/

		range::setPricePerBlock($setting->get("Price Per Block", 0.1));
		//$lang->get("language", ["pos" => ["reset" => "The coordinate data has been deleted."]])

		$array = ["count" => (string)  1000, "price" => (string) 1000, "error" => ""];

		var_dump($lang->translate("RangeSelectForm.price", $array));
	}
}