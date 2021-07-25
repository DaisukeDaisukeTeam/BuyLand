<?php


namespace protect\form;


use pocketmine\Player;
use protect\form\utils\InvalidFormSyntaxException;
use jojoe77777\FormAPI\CustomForm;
use protect\RangeLevel;

class RangeSelectForm extends CustomForm{
	use LangFormTrait;

	public RangeLevel $range;

	/**
	 * RangeSelectForm constructor.
	 */
	public function __construct(RangeLevel $range, string $error = ""){
		parent::__construct(null);
		$this->initLang();

		$this->range = $range;
		[$sx, $sy, $sz, $ex, $ey, $ez] = $range->getRangePos();
		$array = ["sx" => (string) $sx, "sy" => (string) $sy, "sz" => (string) $sz, "ex" => (string) $ex, "ey" => (string) $ey, "ez" => (string) $ez, "count" => (string) $range->CountBlocks(), "price" => (string) $range->calculatePrice(), "error" => $error];

		$this->addLabel($this->translation("RangeSelectForm.price", $array));

		$this->addInput($this->translation("RangeSelectForm.sx", $array), $this->translation("RangeSelectForm.placeholder.sy", $array), (string) $sx);
		$this->addInput($this->translation("RangeSelectForm.sy", $array), $this->translation("RangeSelectForm.placeholder.sy", $array), (string) $sy);
		$this->addInput($this->translation("RangeSelectForm.sz", $array), $this->translation("RangeSelectForm.placeholder.sy", $array), (string) $sz);

		$this->addInput($this->translation("RangeSelectForm.ex", $array), $this->translation("RangeSelectForm.placeholder.sy", $array), (string) $ex);
		$this->addInput($this->translation("RangeSelectForm.ey", $array), $this->translation("RangeSelectForm.placeholder.sy", $array), (string) $ey);
		$this->addInput($this->translation("RangeSelectForm.ez", $array), $this->translation("RangeSelectForm.placeholder.sy", $array), (string) $ez);

	}

	/**
	 * @param Player $player
	 * @param mixed $data
	 */
	public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
		}
		if($data < 6){
			return;
		}
		[$label, $sx, $sy, $sz, $ex, $ey, $ez] = $data;
		try{
			$sx = $this->getInt($sx);
			$sy = $this->getInt($sy);
			$sz = $this->getInt($sz);
			$ex = $this->getInt($ex);
			$ey = $this->getInt($ey);
			$ez = $this->getInt($ez);

			var_dump($sy < 0,$ey < 0,$sy > 255,$ey > 255);
			if($sy < 0||$ey < 0||$sy > 255||$ey > 255){
				throw new InvalidFormSyntaxException("");
			}

			/** @var RangeLevel $range */
			$range = RangeLevel::fromRangePos([$sx, $sy, $sz, $ex, $ey, $ez], true);
			$range->setLevel($this->range->getLevel());//string
			$form = new LandConfirmationForm($range);
			$player->sendForm($form);
		}catch(InvalidFormSyntaxException $exception){
			$form = new self($this->range, $this->translation("RangeSelectForm.error.InvalidSyntax"));
			$player->sendForm($form);
			return;
		}
	}

	/**
	 * @param string|int $value
	 * @return int
	 * @throws InvalidFormSyntaxException
	 */
	public function getInt($value) : int{
		if(!$this->is_natural($value)){
			throw new InvalidFormSyntaxException();
		}
		return ((int) $value);
	}

	/**
	 * @param mixed $val
	 * @return bool
	 */
	public function is_natural($val) : bool{
		if($val === 0||$val === "0"){
			return true;
		}
		return (bool) preg_match('/\A-?[1-9]\d*\z/', $val);
	}
}