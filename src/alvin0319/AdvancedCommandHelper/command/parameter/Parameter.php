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
namespace alvin0319\AdvancedCommandHelper\command\parameter;

use alvin0319\AdvancedCommandHelper\command\parameter\enum\Enum;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\CommandParameter;

abstract class Parameter{

	protected $name;

	protected $optional = false;

	protected $enum = null;

	public function __construct(string $name, bool $optional = false, ?Enum $enum = null){
		$this->name = $name;
		$this->optional = $optional;
		$this->enum = $enum;
	}

	public function getName() : string{
		return $this->name;
	}

	public function isOptional() : bool{
		return $this->optional;
	}

	public function getEnum() : ?Enum{
		return $this->enum;
	}

	public function setEnum(?Enum $enum){
		$this->enum = $enum;
	}

	public function toCommandParameter() : CommandParameter{
		$parameter = new CommandParameter();
		[$parameter->paramName, $parameter->paramType, $parameter->isOptional, $parameter->enum] = [$this->name, AvailableCommandsPacket::ARG_FLAG_VALID | $this->getNetworkType(), $this->optional, ($this->enum instanceof Enum) ? $this->enum->toEnum() : null];
		return $parameter;
	}

	abstract public function getNetworkType() : int;

	public function onAdded() : void{}
}