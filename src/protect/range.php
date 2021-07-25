<?php


namespace protect;


use pocketmine\math\Vector3;

class range{
	/**
	 * @phpstan-var array<string, static>
	 * @var static[]
	 */
	public static array $range = [];

	/**
	 * @phpstan-var array<int, ?Vector3>
	 * @var ?Vector3[]
	 */
	public array $pos = [];

	public static float $pricePerBlock = 1.0;

	final public function __construct(){

	}

	/**
	 * @param array{0: int|float, 1: int|float, 2: int|float, 3: int|float, 4: int|float, 5: int|float} $array
	 * @param bool $sort
	 * @return range
	 */
	public static function fromRangePos(array $array, bool $sort = false) : range{
		$range = new static();
		$range->setPos(0, new Vector3($array[0], $array[1], $array[2]));
		$range->setPos(1, new Vector3($array[3], $array[4], $array[5]));
		if($sort){
			$rangepos = $range->getRangePos();
			if($rangepos === null){
				throw new \LogicException("\$rangepos === null");
			}
			$range->setPos(0, new Vector3($rangepos[0], $rangepos[1], $rangepos[2]));
			$range->setPos(1, new Vector3($rangepos[3], $rangepos[4], $rangepos[5]));
		}
		return $range;
	}

	/**
	 * @param range $range
	 * @return int[]|float[]
	 * @phpstan-return array{0: int|float, 1: int|float, 2: int|float, 3: int|float, 4: int|float, 5: int|float}
	 */
	public static function save(range $range) : array{
		if($range->isCompleted()){
			/** @phpstan-var array{0: int|float, 1: int|float, 2: int|float, 3: int|float, 4: int|float, 5: int|float} */
			return $range->getRangePos();//
		}
		throw new \LogicException("range is not completed");
	}

	/**
	 * @param array{int, int, int} $array
	 * @param array{int, int, int} $array2
	 * @return static
	 */
	public static function fromarray(array $array, array $array2) : self{
		$range = new static();
		$range->setPos(0, new Vector3($array[0], $array[1], $array[2]));
		$range->setPos(1, new Vector3($array2[0], $array2[1], $array2[2]));
		return $range;
	}

	/**
	 * @param String $name
	 * @return static
	 */
	public static function get(string $name) : range{//: static{
		if(!isset(self::$range[$name])){
			self::$range[$name] = new static();
		}
		return self::$range[$name];
	}

	public function hasPos(int $pointer) : bool{
		return isset($this->pos[$pointer]);
	}

	public function setPos(int $pointer, ?Vector3 $pos) : void{
		$this->pos[$pointer] = $pos;
	}

	public function getPos(int $pointer) : ?Vector3{
		return $this->pos[$pointer] ?? null;
	}

	public function unsetPos() : void{
		$this->setPos(0, null);
		$this->setPos(1, null);
	}

	public function isCompleted() : bool{
		return $this->hasPos(0)&&$this->hasPos(1);
	}

	/**
	 * @return null|array{0: int|float, 1: int|float, 2: int|float, 3: int|float, 4: int|float, 5: int|float}
	 */
	public function getRangePos() : ?array{//
		if($this->isCompleted()){
			/** @var Vector3 $pos1 */
			$pos1 = $this->getPos(0);
			/** @var Vector3 $pos2 */
			$pos2 = $this->getPos(1);
			$sx = min($pos1->x, $pos2->x);
			$sy = min($pos1->y, $pos2->y);
			$sz = min($pos1->z, $pos2->z);
			$ex = max($pos1->x, $pos2->x);
			$ey = max($pos1->y, $pos2->y);
			$ez = max($pos1->z, $pos2->z);
			return [$sx, $sy, $sz, $ex, $ey, $ez];
		}
		return null;
	}

	/**
	 * @return float|int|null
	 */
	public function CountBlocks(){
		if($this->isCompleted()){
			/** @var array{0: int|float, 1: int|float, 2: int|float, 3: int|float, 4: int|float, 5: int|float} $rangePos */
			$rangePos = $this->getRangePos();
			return self::CountBlocksByRangePos(...$rangePos);
		}
		return null;
	}

	/**
	 * @param int|float $sx
	 * @param int|float $sy
	 * @param int|float $sz
	 * @param int|float $ex
	 * @param int|float $ey
	 * @param int|float $ez
	 * @return int|float
	 */
	public static function CountBlocksByRangePos($sx, $sy, $sz, $ex, $ey, $ez){
		return ($ex - $sx + 1) * ($ey - $sy + 1) * ($ez - $sz + 1);
	}

	public function isRangeVectorInside(Vector3 $vector, int $expand = 0) : bool{
		list($sx, $sy, $sz, $ex, $ey, $ez) = $this->getRangePos();
		return ($vector->x >= $sx - $expand&&$ex + $expand >= $vector->x)&&($vector->y >= $sy - $expand&&$ey - $expand >= $vector->y)&&($vector->z >= $sz - $expand&&$ez + $expand >= $vector->z);

	}

	/**
	 * @param int $x
	 * @param int|null $y
	 * @param int $z
	 * @return array{0: int|float,1: int|float,2: int|float}
	 */
	public function getCenter($x = 0, ?int $y = null, $z = 0) : array{
		list($sx, $sy, $sz, $ex, $ey, $ez) = $this->getRangePos();
		if($y === null){
			$y = $sy + (($ey - $sy) / 2);
		}
		return [($sx + (($ex - $sx) / 2)) + $x, $y, ($sz + (($ez - $sz) / 2)) + $z];
	}

	public function __toString() : string{
		if($this->isCompleted()){
			list($sx, $sy, $sz, $ex, $ey, $ez) = $this->getRangePos();
			return "sx: ".$sx.", sy: ".$sy.", sz: ".$sz.", ex: ".$ex.", ey: ".$ey.", ez: ".$ez;
		}
		$result = "";
		$pos1 = $this->getPos(0);
		$pos2 = $this->getPos(1);
		if($pos1 instanceof Vector3){
			$result .= "sx: ".$pos1->x.", sy: ".$pos1->y.", sz: ".$pos1->z;
		}else{
			$result .= "sx: null, sy: null, sz: null";
		}
		if($pos2 instanceof Vector3){
			$result .= ", ex: ".$pos2->x.", ey: ".$pos2->y.", ez: ".$pos2->z;
		}else{
			$result .= ", ex: null, ey: null, ez: null";
		}
		return $result;
	}

	public function calculatePrice() : int{
		$price = (int) ($this->CountBlocks() * self::$pricePerBlock);
		if($price <= 0){
			return 1;
		}
		return $price;
	}

	/*
	 getChunk(); => rule
	 */
	/*public function CountBlocksByRangePos(): ?int{
		if($this->isCompleted()){
			list($sx,$sy,$sz,$ex,$ey,$ez) = $this->getRangePos();
			return ($ex - $sx + 1) * ($ey - $sy +1) * ($ez - $sz + 1);
		}
		return null;
	}*/

	/*public function toAxisAlignedBB(): AxisAlignedBB{
		return
	}*/

	/**
	 * @return float
	 */
	public static function getPricePerBlock() : float{
		return self::$pricePerBlock;
	}

	/**
	 * @param float $pricePerBlock
	 */
	public static function setPricePerBlock(float $pricePerBlock) : void{
		self::$pricePerBlock = $pricePerBlock;
	}

}