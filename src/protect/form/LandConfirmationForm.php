<?php


namespace protect\form;


use pocketmine\Player;
use jojoe77777\FormAPI\ModalForm;
use protect\Protect;
use protect\RangeLevel;

class LandConfirmationForm extends ModalForm{
	use LangFormTrait;

	public RangeLevel $range;

	public function __construct(RangeLevel $range, string $error = ""){
		parent::__construct(null);
		$this->initLang();

		$this->range = $range;
		[$sx, $sy, $sz, $ex, $ey, $ez] = $range->getRangePos();
		$array = ["sx" => (string) $sx, "sy" => (string) $sy, "sz" => (string) $sz, "ex" => (string) $ex, "ey" => (string) $ey, "ez" => (string) $ez, "count" => (string) $range->CountBlocks(), "price" => "§e".$range->calculatePrice()."§r", "error" => $error];

		$this->setContent($this->translation("LandConfirmationForm.label", $array));
		$this->setButton1($this->translation("LandConfirmationForm.buy", $array));
		$this->setButton2($this->translation("LandConfirmationForm.cancel", $array));
	}

	/**
	 * @param Player $player
	 * @param ?bool $data
	 */
	public function handleResponse(Player $player, $data) : void{
		if($data === null||$data === false){
			$this->sendMessage($player, "LandConfirmationForm.cancelled");
			return;
		}
		$this->sendMessage($player, "LandConfirmationForm.bought",["price" => "§e".$this->range->calculatePrice()."§r"]);
		Protect::getInstance()->addProtect($player, $this->range);
	}
}