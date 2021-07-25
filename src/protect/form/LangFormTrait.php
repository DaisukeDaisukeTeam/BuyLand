<?php


namespace protect\form;


use pocketmine\Player;
use jojoe77777\FormAPI\CustomForm;
use pjz9n\libi18n\LangHolder;
use pjz9n\libi18n\LangInstance;

trait LangFormTrait{
	public LangInstance $lang;
	public function initLang(): void{
		$this->lang = LangHolder::get();
	}

	public function getLang() : LangInstance{
		return $this->lang;
	}

	/**
	 * @param mixed $key
	 * @param array<mixed> $parameters
	 * @return string
	 */
	public function translation($key, array $parameters = []) : string{
		return $this->lang->translate($key, $parameters);
	}

	/**
	 * @param Player $player
	 * @param mixed $key
	 * @param array<mixed> $parameters
	 */
	public function sendMessage(Player $player, $key, array $parameters = []) : void{
		$player->sendMessage($this->translation($key, $parameters));
	}
}