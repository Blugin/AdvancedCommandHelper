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

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class FloatParameter extends Parameter{

	public function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_TYPE_FLOAT;
	}
}