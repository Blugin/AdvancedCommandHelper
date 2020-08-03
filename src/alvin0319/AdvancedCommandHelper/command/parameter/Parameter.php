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
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

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
		if($this->enum instanceof Enum){
			$parameter = CommandParameter::enum($this->name, $this->enum->toEnum(), 0, $this->optional);
			$parameter->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | $this->getNetworkType();
			return $parameter;
		}else{
			return CommandParameter::standard($this->name, AvailableCommandsPacket::ARG_FLAG_VALID | $this->getNetworkType(), 0, $this->optional);
		}
	}

	abstract public function getNetworkType() : int;

	public function onAdded() : void{}
}