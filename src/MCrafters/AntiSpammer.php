<?php
namespace Mcrafters;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\Listener;

class AntiSpammer extends PluginBase implements Listener{

    private $players = [];
    private $warnings = [];
    
    public function onChat(PlayerChatEvent $e){
        if($e->getPlayer()->hasPermission("spam.bypass")) return;
        if(isset($this->players[spl_object_hash($e->getPlayer())]) and
            (time() - $this->players[spl_object_hash($e->getPlayer())] <= intval($this->getConfig()->get("time")))){
            if(!isset($this->warnings[spl_object_hash($e->getPlayer())])){
                $this->warnings[spl_object_hash($e->getPlayer())] = 0;
            }
            ++$this->warnings[spl_object_hash($e->getPlayer())];
            $e->getPlayer()->sendMessage(str_replace("%warns%", $this->warnings[spl_object_hash($e->getPlayer())],
                FMT::colorMessage($this->getConfig()->getAll(){"warning_message"})));
            $e->setCancelled();
            if($this->warnings[spl_object_hash($e->getPlayer())] >= intval($this->getConfig()->get("max_warnings"))){
               $e->getPlayer()->sendMessage(str_replace("%player%", $e->getPlayer()->getName(), FMT::colorMessage($this->getConfig()->getAll(){"message"})));
               unset($this->warnings[spl_object_hash($e->getPlayer())]);
               $e->setCancelled();
            }
        } else{
            $this->players[spl_object_hash($e->getPlayer())] = time();
        }
    }
}