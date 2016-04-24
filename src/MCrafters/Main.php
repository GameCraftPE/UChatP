<?php

namespace Mcrafters;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "UChatP Has Been Enabled.");
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "By: Mcrafterss. http://github.com/MCrafterss");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
                $this->saveDefaultConfig();
                $this->reloadConfig();
		$this->mosaicList = array();
		foreach($this->getConfig()->get("messages") as $m){
			$this->mosaicList[] = str_repeat($this->getConfig()->get("mosaic"), strlen($m));
		}
	}
	
	public function onChatEvent(PlayerChatEvent $event){
		$message = $event->getMessage();
		$event->setMessage($this->getConfig()->get("identify-capital-alphabet") ? str_replace($this->getConfig()->get("messages"), $this->mosaicList, $message) : str_ireplace($this->getConfig()->get("messages"), $this->mosaicList, $message));
	}
}
