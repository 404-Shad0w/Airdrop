<?php

namespace shadow;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use shadow\manager\Manager;

class Loader extends PluginBase
{
    use SingletonTrait;

    private Manager $manager;

    protected function onLoad(): void{self::setInstance($this);}

    protected function onEnable(): void
    {
        $this->manager = new Manager();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
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