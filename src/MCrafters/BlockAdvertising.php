<?php
namespace MCrafters;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener{

private $webEndings = array(".net",".com",".co",".org",".info",".tk"); 
        
    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        $playername = $event->getPlayer()->getDisplayName();
        //----------------------------
        $parts = explode('.', $message);
        if(sizeof($parts) >= 4)
        {
            if (preg_match('/[0-9]+/', $parts[1]))
            {
                $event->setCancelled(true);
                $player->kick("Advertising");
            }
        }
        //----------------------------
        foreach ($this->webEndings as $url) {
            if (strpos($message, $url) !== FALSE) 
            {
                $event->setCancelled(true);
                $player->kick("Advertising");
            }
        }
        //----------------------------
        
    }
    }
