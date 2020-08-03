<?php

/*
 *         _       _        ___ _____ _  ___
 *    __ _| |_   _(_)_ __  / _ \___ // |/ _ \
 *   / _` | \ \ / / | '_ \| | | ||_ \| | (_) |
 *  | (_| | |\ V /| | | | | |_| |__) | |\__, |
 *   \__,_|_| \_/ |_|_| |_|\___/____/|_|  /_/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Blugin team (alvin0319)
 */

declare(strict_types=1);
namespace alvin0319\AdvancedCommandHelper;

use alvin0319\AdvancedCommandHelper\command\AdvancedCommand;
use alvin0319\AdvancedCommandHelper\command\parameter\IntegerParameter;
use alvin0319\AdvancedCommandHelper\command\parameter\PlayerParameter;
use alvin0319\AdvancedCommandHelper\command\parameter\TextParameter;
use alvin0319\AdvancedCommandHelper\command\parameter\Vector3Parameter;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use function implode;

class AdvancedCommandHelper extends PluginBase implements Listener{
	use SingletonTrait;

	/** @var AdvancedCommand[] */
	protected $commands = [];

	public function onLoad() : void{
		self::setInstance($this);
	}

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if($this->getDescription()->getVersion() === "0.0.1"){ // Test version
			$this->getServer()->getCommandMap()->register("AdvancedCommandHelper", new class extends AdvancedCommand{

				public function __construct(){
					parent::__construct("test", "A test command");
				}

				public function getOwningPlugin() : Plugin{
					return AdvancedCommandHelper::getInstance();
				}

				public function prepare() : void{
					$this->addParameter(0, new TextParameter("text", false));
					$this->addParameter(1, new IntegerParameter("test1", false));
					$this->addParameter(0, new PlayerParameter("player", false));
					$this->addParameter(1, new Vector3Parameter("position", false));
				}

				public function onRun(CommandSender $sender, string $commandLabel, array $args): bool{
					$sender->sendMessage(implode(" ", $args));
					return true;
				}
			});
		}
	}

	public function addCommand(AdvancedCommand $command) : void{
		$this->commands[$command->getName()] = $command;
	}

	/**
	 * @param DataPacketSendEvent $event
	 * @priority HIGHEST
	 */
	public function onDataPacketSend(DataPacketSendEvent $event) : void{
		$packets = $event->getPackets();
		$packet = $packets[0] ?? null;
		$targets = $event->getTargets();
		$networkSession = $targets[0] ?? null;
		if($packet instanceof AvailableCommandsPacket){
			if($networkSession instanceof NetworkSession){
				$player = $networkSession->getPlayer();
				if($player instanceof Player){
					foreach($this->commands as $name => $command){
						if(isset($packet->commandData[$command->getName()])){
							$parameters = $command->getParameters();
							/** @var CommandParameter[][] $overloads */
							$overloads = [];
							foreach($parameters as $position => $parameterList){
								if(!isset($overloads[$position])){
									$overloads[$position] = [];
								}
								foreach($parameterList as $parameter){
									$parameter->onAdded();
									$overloads[$position][] = $parameter->toCommandParameter();
								}
							}
							$data = $packet->commandData[$command->getName()];
							$data->overloads = $overloads;
							$packet->commandData[$command->getName()] = $data;
						}
					}
				}
			}
		}
	}


	public function onPlayerJoin(PlayerJoinEvent $_) : void{
		foreach($this->getServer()->getOnlinePlayers() as $player)
			$player->getNetworkSession()->syncAvailableCommands();
	}

	public function onPlayerQuit(PlayerQuitEvent $_) : void{
		foreach($this->getServer()->getOnlinePlayers() as $player)
			$player->getNetworkSession()->syncAvailableCommands();
	}
}