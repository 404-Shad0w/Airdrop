<?php

namespace shadow;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use shadow\commands\EditCommand;
use shadow\commands\GiveCommand;
use shadow\manager\Manager;
use muqsit\invmenu\InvMenuHandler;

class Loader extends PluginBase
{
    use SingletonTrait;

    private Manager $manager;

    public function onLoad(): void{self::setInstance($this);}

    public function onEnable(): void
    {
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }
        
        $this->manager = new Manager();
        $this->getServer()->getPluginManager()->registerEvents(new Events(), $this);
        $this->getServer()->getCommandMap()->registerAll("airdrops", [
            new GiveCommand(),
            new EditCommand()
        ]);
        $this->getLogger()->info("Shadow plugin enabled.");
    }

    /**
     * @return Manager
     */
    public function getManager(): Manager
    {
        return $this->manager;
    }
}