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
namespace alvin0319\AdvancedCommandHelper\command\parameter\enum;

use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use function array_values;

class Enum{

    protected $name;

    protected $enumValues = [];

    public function __construct(string $name, array $enumValues){
        $this->name = $name;
        $this->enumValues = $enumValues;
    }

    public function toEnum() : CommandEnum{
        return new CommandEnum($this->name, array_values($this->enumValues));
    }

    public static function fromEnum(CommandEnum $enum) : Enum{
        return new Enum($enum->getName(), $enum->getValues());
    }
}