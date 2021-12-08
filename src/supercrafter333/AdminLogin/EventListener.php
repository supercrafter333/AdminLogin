<?php

namespace supercrafter333\AdminLogin;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\sound\XpLevelUpSound;

/**
 *
 */
class EventListener implements Listener
{

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $plugin = AdminLoginLoader::getInstance();
        $msgs = $plugin->getMessageConfigFile();
        $mgr = $plugin->getPurePermsUserMgr();
        $groupname = $plugin->getPurePermsUserGroupName($player);
        $config = $plugin->getConfigFile();
        if ($this->checkGroup($player) == true) {
            $this->AdminLoginForm($player);
        }
    }

    /**
     * @param Player $player
     * @return CustomForm
     */
    public function AdminLoginForm(Player $player): CustomForm
    {
        $config = AdminLoginLoader::getInstance()->getConfigFile();
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
     * @return EventListener
     */
    public static function getListener(): EventListener
    {
        return new EventListener();
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function checkGroup(Player $player): bool
    {
        $plugin = AdminLoginLoader::getInstance();
        $msgs = AdminLoginLoader::getInstance()->getMessageConfigFile();
        $mgr = $plugin->getPurePermsUserMgr();
        $groupname = $plugin->getPurePermsUserGroupName($player);
        $config = $plugin->getConfigFile();
        if ($config->exists($groupname)) {
            return true;
        }
        return false;
    }

    /**
     * @var
     */
    protected $code;

    /**
     * @param Player $player
     * @param $index
     */
    public function checkGroupAndKey(Player $player, $index)
    {
        $plugin = AdminLoginLoader::getInstance();
        $msgs = AdminLoginLoader::getInstance()->getMessageConfigFile();
        $mgr = $plugin->getPurePermsUserMgr();
        $groupname = $plugin->getPurePermsUserGroupName($player);
        $config = AdminLoginLoader::getInstance()->getConfigFile();
        if ($config->exists($groupname)) {
            $code = $config->get($groupname)["code"];
            $indexstring = "$index[1]";
            $plugin->getServer()->getLogger()->info($code);
            if ($indexstring === $code) {
                $this->trueCode($player);
            } else {
                $this->falseCode($player);
            }
        }
    }

    /**
     * @param Player $player
     */
    public function trueCode(Player $player)
    {
        $plugin = AdminLoginLoader::getInstance();
        $msgs = AdminLoginLoader::getInstance()->getMessageConfigFile();
        $player->sendMessage($msgs->get("msg-right-code"));
        $player->broadcastSound(new XpLevelUpSound(mt_rand()), [$player]);
    }

    /**
     * @param Player $player
     */
    public function falseCode(Player $player)
    {
        $plugin = AdminLoginLoader::getInstance();
        $msgs = AdminLoginLoader::getInstance()->getMessageConfigFile();
        $player->kick($msgs->get("msg-false-code-kickmsg"), false);
    }
    /*End of API part*/
}