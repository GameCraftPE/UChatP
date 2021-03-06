<?php

namespace Mcrafters;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

class Main extends PluginBase implements Listener{

	private $webEndings = array(".net",".com",".co",".org",".info",".tk",".ml",".ga",".au",".uk",".me",".sa");
	private $players = [];
	private $warnings = [];

	public function onEnable(){
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "UChatP Has Been Enabled.");
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "Plugin Made By: Mcrafterss. http://github.com/MCrafterss");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->reloadConfig();
		$this->mosaicList = array();
		foreach($this->getConfig()->get("messages") as $m){
			$this->mosaicList[] = str_repeat($this->getConfig()->get("mosaic"), strlen($m));
		}
	}

	public function onChatEvent(PlayerChatEvent $e){
		// filter bad words
		$message = $e->getMessage();
		$e->setMessage($this->getConfig()->get("identify-capital-alphabet") ? str_replace($this->getConfig()->get("messages"), $this->mosaicList, $message) : str_ireplace($this->getConfig()->get("messages"), $this->mosaicList, $message));
		// filter spam
		if(isset($this->players[spl_object_hash($e->getPlayer())]) and (time() - $this->players[spl_object_hash($e->getPlayer())] <= intval($this->getConfig()->get("time")))){
			if(!isset($this->warnings[spl_object_hash($e->getPlayer())])){
				$this->warnings[spl_object_hash($e->getPlayer())] = 0;
			}
			++$this->warnings[spl_object_hash($e->getPlayer())];
			$e->getPlayer()->sendMessage(str_replace("%warns%", $this->warnings[spl_object_hash($e->getPlayer())], $this->getConfig()->getAll(){"warning_message"}));
			$e->setCancelled();
			if($this->warnings[spl_object_hash($e->getPlayer())] >= intval($this->getConfig()->get("max_warnings"))){
				$e->getPlayer()->kick(str_replace("%player%", $e->getPlayer()->getName(), $this->getConfig()->getAll(){"kick_reason"}));
				unset($this->warnings[spl_object_hash($e->getPlayer())]);
				$e->setCancelled();
			}
		}else{
			$this->players[spl_object_hash($e->getPlayer())] = time();
		}
		$parts = explode('.', $message);
		if(count($parts) >= 2){
			if (preg_match('/[0-9]+/', $parts[1])){
				$e->setCancelled(true);
				$e->getPlayer()->kick("§cAdvertising");
			}
		}
		foreach ($this->webEndings as $url) {
			if (strpos($message, $url) !== FALSE){
				$e->setCancelled(true);
				$e->getPlayer()->kick("§cAdvertising");
			}
		}
	}
	if($e->isCancelled())
			return;
		$player = $e->getPlayer();
		$recipients = $e->getRecipients();
		foreach($recipients as $key => $recipient){
			if($recipient instanceof Player){
				if($recipient->getLevel()->getName() != $player->getLevel()->getName()){
					unset($recipients[$key]);
				}
			}
		}
		$e->setRecipients($recipients);
}
