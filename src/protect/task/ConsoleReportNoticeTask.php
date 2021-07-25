<?php


namespace protect\task;


use pocketmine\plugin\PluginLogger;
use pocketmine\scheduler\Task;

class ConsoleReportNoticeTask extends Task{
	public string $message;
	public PluginLogger $logger;

	public function __construct(PluginLogger $logger, string $message){
		$this->logger = $logger;
		$this->message = $message;
	}

	/**
	 * @param int $currentTick
	 * @return void
	 */
	public function onRun(int $currentTick){
		$this->getLogger()->notice($this->message);
	}

	public function getLogger() : PluginLogger{
		return $this->logger;
	}
}