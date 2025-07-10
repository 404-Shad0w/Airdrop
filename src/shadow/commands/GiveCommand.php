<?php

namespace shadow\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;
use shadow\utils\Messages;
use shadow\utils\Utils;

class GiveCommand extends Command
{

    public function __construct()
    {
        parent::__construct('airdropgive', 'Gives an airdrop to a player', 'Usage: /give <player> [amount]');
        $this->setPermission('airdropgive.perm');
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $sender, string $label, array $args)
    {
        if (!$sender instanceof Player)return;

        $player = Server::getInstance()->getPlayerExact($args[0] ?? '');
        $amount = (int)$args[1] ?? 1;

        if (empty($player)){
            $msg = str_replace("{argument}", "player", Messages::EMPTY_ARGUMENT);
            $sender->sendMessage($msg);
            return;
        }

        if (empty($amount)) {
            $msg = str_replace("{argument}", "amount", Messages::EMPTY_ARGUMENT);
            $sender->sendMessage($msg);
            return;
        }

        if (!is_numeric($amount) || $amount === 0) {
            $msg = str_replace("{argument}", "amount", Messages::INVALID_ARGUMENT);
            $sender->sendMessage($msg);
            return;
        }


        if ($player === null) {
            $sender->sendMessage(Messages::PLAYER_NOT_FOUND);
            return;
        }

        if (!$player->isOnline()) {
            $sender->sendMessage(Messages::PLAYER_NOT_FOUND);
            return;
        }

        Utils::giveAirdrop($player, $amount);
    }
}