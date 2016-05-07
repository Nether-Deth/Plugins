<?php

namespace TheKiller\listener;

use TheKiller\database\PluginData;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use ExamplePlugin\listener\other\ListenerLoader;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Server;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;

class EventListener implements Listener {
	/**
	 *
	 * @var Plugin
	 */
	private $plugin;
	private $db;
	private $listenerloader;
	/**
	 *
	 * @var Server
	 */
	private $server;
	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
		$this->db = PluginData::getInstance ();
		$this->listenerloader = ListenerLoader::getInstance ();
		$this->server = Server::getInstance ();
		
		$this->registerCommand("killer", "killer.command.allow", "killer-description", "killer-help");
		$this->registerCommand("start", "start.command.allow", "start-description", "start-help");
		$this->registerCommand("stop", "stop.command.allow", "stop-description", "stop-help");
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $plugin );
	}
	public function isKiller(Player $player){
		if($player->getInventory()->contains(Item::get(Item::DIAMOND_SWORD,0,1))){
			return true;
		}
		else{
			return false;
		}
	}
	public function onDeath(PlayerDeathEvent $event){
		$event->setDeathMessage( $this->db->get('killer-deathmessage'));
		if($event->getEntity()->getName()==$this->db->db ['killer']){
			$this->db->db ['killer']='none';
		}
	}
	public function registerCommand($name, $permission, $description, $usage) {
		$name = $this->db->get ( $name );
		$description = $this->db->get ( $description );
		$usage = $this->db->get ( $usage );
		$this->db->registerCommand ( $name, $permission, $description, $usage );
	}
	public function getServer() {
		return $this->server;
	}
	public function onCommand(CommandSender $player, Command $command, $label, array $args) {
		// TODO - 명령어처리용
		if (strtolower ( $command ) == $this->db->get ( "killer" )) { // TODO <- 빈칸에 명령어
			if($this->isKiller($player)){
				if($this->db->db ['killer']=='none'){
					$this->db->db ['killer']=$player->getName();
					switch($this->db->db ['isactive']){
						case 'yes':
							$this->db->db ['isactive']='no';
							$player->sendMessage('살인자 능력 활성화');
						case 'no':
							$this->db->db ['isactive']='yes';
							$player->sendMessage('살인자 능력 활성화');
					}
				}
			}
			return true;
		}
		if (strtolower ( $command ) == $this->db->get ( "start" )) {
			if($this->db->db['started']=='no'){
				$this->db->db['started']=='yes';
				$players=$this->server->getOnlinePlayers();
				foreach($players as $pls){
					$this->db->alert($pls,'게임이 시작되었습니다.');
					$this->plugin->startgame(true);
				}
			}
			else{
			$this->db->alert($player,'게임이 이미 시작되었습니다.');
		}
	}
	if (strtolower ( $command ) == $this->db->get ( "start" )) {
		if($this->db->db['started']=='yes'){
			$this->db->db['started']=='no';
			$players=$this->server->getOnlinePlayers();
			foreach($players as $pls){
				$this->db->alert($pls,'게임이 종료되었습니다.');
				$this->plugin->startgame(false);
			}
		}
		else{
			$this->db->alert($player,'게임이 이미 종료되었습니다.');
	}
	}
	}
	public function onTouch(PlayerInteractEvent $event){
		$player=$event->getPlayer();
		$name=$player->getName();
		if($this->db->db ['killer']==$name){
			if($this->db->db ['isactive']=='yes'){
			$player->addEffect ( Effect::getEffect ( Effect::INVISIBITTITY)->setAmplifier (1)->setDuration (99999) );
		}
		else{
			$player->addEffect ( Effect::getEffect ( Effect::INVISIBITTITY)->setAmplifier (2)->setDuration (1) );
		}
		}
	}
}

?>
