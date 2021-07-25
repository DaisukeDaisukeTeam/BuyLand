<?php

namespace protect\provider\api;

use protect\provider\ProviderBase;

class PocketMoney_Provider extends ProviderBase{
	public static $pluginName = "PocketMoney";

	public function myMoney($player): float{
		return $this->MoneyAPI->getMoney($this->getTranslatedName($player));
	}

	public function setMoney($player,float $money){
		$this->MoneyAPI->setMoney($this->getTranslatedName($player),$money);
	}

	public function addMoney($player,float $money){
		$this->MoneyAPI->grantMoney($this->getTranslatedName($player),$money);
	}

	public function reduceMoney($player,float $money){
		$money = $this->myMoney($player) - $money;
		if($money < 0){
			$money = 0;
		}
		$this->setMoney($player,$money);
	}

	public function existMoney($player, float $money): bool{
		return $this->myMoney($player) >= $money;
	}
}
