<?php

namespace TheKiller;

use TheKiller\database\PluginData;
use TheKiller\listener\EventListener;
use TheKiller\listener\other\ListenerLoader;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use TheKiller\task\AutoSaveTask;

class TheKiller extends PluginBase implements Listener {
	private $database;
	private $eventListener;
	private $listenerLoader;
	/**
	 * Called when the plugin is enabled
	 *
	 * @see \pocketmine\plugin\PluginBase::onEnable()
	 */
	public function onEnable() {
		$this->database = new PluginData ( $this );
		$this->eventListener = new EventListener ( $this );
		$this->listenerLoader = new ListenerLoader ( $this );
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
		$this->getServer ()->getScheduler ()->scheduleRepeatingTask ( new AutoSaveTask ( $this ), 12000 );
		$this->getLogger()->info(TextFormat::GREEN . "살인자 플러그인이 활성화 되었습니다.");
	}
	public function startgame($bool){
		$handle=$this->getServer()->getScheduler()->scheduleDelayedRepeatingTask( new GameTask($this));
		if($bool==true){
			$this->db ['started']='yes';
			$handle->run($handle);
		}
	else{
		$handle->cancel();
	}
	}
		
	public function onLoad() {
		$this->getLogger()->info(TextFormat::BLUE . "살인자 플러그인 로딩중...");
	}
	public function onDisable() {
		$this->getLogger()->info(TextFormat::GREEN . "살인자 플러그인이 비활성화 되었습니다");
	$this->save ();
	}
	/**
	 * Save plug-in configs
	 *
	 * @param string $async        	
	 */
	public function save($async = false) {
		$this->database->save ( $async );
	}
	/**
	 * Handles the received command
	 *
	 * @see \pocketmine\plugin\PluginBase::onCommand()
	 */
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		return $this->eventListener->onCommand ( $sender, $command, $label, $args );
	}
	/**
	 * Return Plug-in Database
	 */
	public function getDataBase() {
		return $this->database;
	}
	/**
	 * Return Plug-in Event Listener
	 */
	public function getEventListener() {
		return $this->eventListener;
	}
	/**
	 * Return Other Plug-in Event Listener
	 */
	public function getListenerLoader() {
		return $this->listenerLoader;
	}
}

?>