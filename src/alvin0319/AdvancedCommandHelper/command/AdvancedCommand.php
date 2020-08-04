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
namespace alvin0319\AdvancedCommandHelper\command;

use alvin0319\AdvancedCommandHelper\AdvancedCommandHelper;
use alvin0319\AdvancedCommandHelper\command\parameter\Parameter;
use pocketmine\command\Command;
use pocketmine\command\CommandMap;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

abstract class AdvancedCommand extends Command implements PluginIdentifiableCommand{

	/** @var Parameter[][] */
	protected $parameters = [];

	public function __construct(string $name, string $description = "", ?string $usageMessage = null, array $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->prepare();
	}

	public function prepare() : void{}

	final public function register(CommandMap $commandMap) : bool{
		if(parent::register($commandMap)){
			AdvancedCommandHelper::getInstance()->addCommand($this);
			return true;
		}
		return false;
	}

	public function addParameter(int $position, Parameter $parameter) : void{
		if(!isset($this->parameters[$position])){
			$this->parameters[$position] = [];
		}
		$this->parameters[$position][] = $parameter;
	}

	/**
	 * @return Parameter[][] position => parameter
	 */
	public function getParameters() : array{
		return $this->parameters;
	}

	final public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		return $this->onRun($sender, $commandLabel, $args);
	}

	abstract public function onRun(CommandSender $sender, string $commandLabel, array $args) : bool;
}