<?php

namespace Mcrafters;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\player\PlayerChatEvent;
class Main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "UChatP Has Been Enabled.");
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "By: Mcrafterss. http://github.com/MCrafterss");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
		    $this->saveDefaultConfig();
	}


	public function onChatEvent(PlayerChatEvent $event){
		$message = $event->getMessage();
		$event->setMessage(false ? str_replace($this->config["messages"], *, $message) : str_ireplace($this->config["messages"], *, $message));
	}
