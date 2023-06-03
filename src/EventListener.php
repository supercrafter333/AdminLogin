<?php

namespace supercrafter333\AdminLogin;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\sound\XpLevelUpSound;
use supercrafter333\AdminLogin\groupPlugin\GroupPluginManager;

class EventListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        if ($this->checkGroup($player)) $this->AdminLoginForm($player);
    }

    /**
     * @param Player $player
     * @return CustomForm
     */
    public function AdminLoginForm(Player $player): CustomForm
    {
        $msgs = new Config(AdminLoginLoader::getInstance()->getDataFolder() . "messages.yml", Config::YAML);
        $form = new CustomForm(function (Player $player, array $data = null) {
            $msgs = AdminLoginLoader::getInstance()->getMessageConfigFile();
            if($data === null) {
                $msxx = $msgs->get("msg-false-code-kickmsg");
                $player->kick($msxx, false);
            }
            $index = $data;
            $this->checkGroupAndKey($player, $index);
        });
        $form->setTitle($msgs->get("ui-title"));
        $form->addLabel($msgs->get("ui-content"));
        $form->addInput("", $msgs->get("ui-key-placeholder"));
        $form->sendToPlayer($player);
        return $form;
    }

    /*API Part*/

    /**
     * @param Player $player
     * @return bool
     */
    public function checkGroup(Player $player): bool
    {
        $groupname = GroupPluginManager::getGroupPlugin()->getGroupNameByPlayer($player);
        $config = AdminLoginLoader::getInstance()->getConfigFile();
        return $config->exists($groupname);
    }

    protected $code;

    /**
     * @param Player $player
     * @param string $index
     */
    public function checkGroupAndKey(Player $player, string $index): void
    {
        $plugin = AdminLoginLoader::getInstance();
        $groupname = GroupPluginManager::getGroupPlugin()->getGroupNameByPlayer($player);
        $config = AdminLoginLoader::getInstance()->getConfigFile();

        if ($config->exists($groupname)) {
            $code = $config->get($groupname)["code"];
            $indexstring = "$index[1]";
            $plugin->getServer()->getLogger()->info($code);

            if ($indexstring === $code) $this->trueCode($player);
            else $this->falseCode($player);
        }
    }

    /**
     * @param Player $player
     */
    public function trueCode(Player $player): void
    {
        $msgs = AdminLoginLoader::getInstance()->getMessageConfigFile();
        $player->sendMessage($msgs->get("msg-right-code"));
        $player->broadcastSound(new XpLevelUpSound(mt_rand()), [$player]);
    }

    /**
     * @param Player $player
     */
    public function falseCode(Player $player): void
    {
        $msgs = AdminLoginLoader::getInstance()->getMessageConfigFile();
        $player->kick($msgs->get("msg-false-code-kickmsg"), false);
    }
    /*End of API part*/
}