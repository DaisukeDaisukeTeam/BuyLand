<?php

namespace protect\provider\api;

use protect\provider\ProviderBase;

class EconomyAPI_Provider extends ProviderBase{
	public static $pluginName = "EconomyAPI";

	public function myMoney($player): float{
		return $this->MoneyAPI->myMoney($player);
	}

	public function setMoney($player,float $money){
		$this->MoneyAPI->setMoney($player, $money);
	}
	
	public function addMoney($player, float $money){
		$this->MoneyAPI->addMoney($player,$money);
	}

	public function reduceMoney($player, float $money){
		$this->MoneyAPI->reduceMoney($player,$money);
	}

	public function existMoney($player, float $money): bool{
		return $this->myMoney($player) >= $money;
	}
}
