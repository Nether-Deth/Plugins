<?php
//
namespace TheKiler\task;

use pocketmine\scheduler\PluginTask;
use TheKiller\TheKiller;
use TheKiller\listener\EventListener;
use pocketmine\Player;
use TheKiller\database\PluginData;

$this->plugin = $plugin;
$this->db = PluginData::getInstance ();
$this->listenerloader = ListenerLoader::getInstance ();
$this->server = Server::getInstance ();

class GameTask extends PluginTask {
	protected $owner;
	public function __construct(Plugin $owner) {
		parent::__construct ( $owner );
	}
	public function onRun($currentTick) {
	$this->db->db ['cankill']='yes';
	$players=$this->server->getOnlinePlayers();
	foreach($players as $pls){
		$this->db->alert($pls,'살인자는 10분마다 살인을 저지를수 있습니다.');
	}
	}
}
?>