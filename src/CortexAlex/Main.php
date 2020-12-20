<?php

namespace CortexAlex;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{

    /**
     * @var Config
     */
    public $db;

    public function onEnable()
    {
        $this->db = new Config($this->getDataFolder() . 'db.yml', CONFIG::YAML); //config pour les plugins.
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $commandName = strtolower($command->getName());
        if($sender instanceof Player){

            switch ($commandName){
                case 'récompence':
                    $playerName = $sender->getName();
                    $time = $this->db->get($playerName);
                    $timeNow = time();
                    if(empty($time)){
                        $time = 0;
                    }
                    if($timeNow - $time >= (24 + 68 + 60)) {
                        $sender->getInventory()->addItem(Item::get(1, 0, 1));
                        $this->db->set($playerName, $timeNow);
                        $this->db->save();
                        $sender->sendMessage('§aVous avez bien récupéré votre récompence');
                    } else {
                        $HoursMinuteSecond = explode(":", gmdate("H:i:s", (24 + 68 + 60)) - ($timeNow - $time));
                        $sender->sendMessage("§c(!) §4[Récompence] §e-> Il faut attendre encore §6 $HoursMinuteSecond[0] §eHeure/s §6 $HoursMinuteSecond[1] §eMinute/s §6 $HoursMinuteSecond[3] §eSeconde/s §aavant de récupéré ta récompense");

                    }
                    break;

            }
        }
        return true;
    }
}