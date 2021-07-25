<?php


namespace protect;


class RangeLevel extends range{
	/** @var ?string $level */
	public ?string $level = null;

	/**
	 * @return string ワールド名
	 */
	public function getLevel(): ?string{
		return $this->level;
	}

	/**
	 * @return string ワールド名
	 */
	public function getLevelNonNull() : string{
		$world = $this->getLevel();
		if($world === null){
			throw new \RuntimeException("Position world is null");
		}
		return $world;
	}

	/**
	 * @param string|null $level ワールド名
	 */
	public function setLevel(?string $level): void{
		$this->level = $level;
	}

	public function unsetPos() : void{
		parent::unsetPos();
		$this->setLevel(null);
	}
}