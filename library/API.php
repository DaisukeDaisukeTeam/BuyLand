<?php

namespace metowa1227\moneysystem\api\core;

class API{
	private static ?API $instance = null;

	public static function getInstance() : API{}

	public function get(string $name) : int{}
	public function set(string $name, int $money) : void{}
	public function increase(string $name, int $money) : void{}
	public function reduce(string $name, int $money) : void{}
}