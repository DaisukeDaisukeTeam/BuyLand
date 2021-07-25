<?php

namespace protect\command;

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use protect\form\RangeSelectForm;
use CortexPE\Commando\BaseCommand;
use pjz9n\libi18n\LangHolder;
use protect\RangeLevel;

class BuyLandCommand extends BaseCommand{
	protected function prepare() : void{

	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		/** @var ConsoleCommandSender|Player $sender */
		if($sender instanceof ConsoleCommandSender){
			$sender->sendMessage(LangHolder::get()->translate("internal.command.console"));
			return;
		}
		$range = RangeLevel::get($sender->getName());
		if(!$range->isCompleted()){
			$sender->sendMessage(LangHolder::get()->translate("command.BuyLandCommand.notcompleted"));
			return;
		}
		$form = new RangeSelectForm($range);
		$sender->sendForm($form);
	}
}