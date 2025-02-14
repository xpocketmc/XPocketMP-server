<?php

/*
 *
 *  __  ______            _        _   __  __ ____
 *  \ \/ /  _ \ ___   ___| | _____| |_|  \/  |  _ \
 *   \  /| |_) / _ \ / __| |/ / _ \ __| |\/| | |_) |
 *   /  \|  __/ (_) | (__|   <  __/ |_| |  | |  __/
 *  /_/\_\_|   \___/ \___|_|\_\___|\__|_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License as published by
 * the Free Software Foundation
 * The files in XPocketMP are mostly from PocketMine-MP.
 * Developed by ClousClouds, PMMP Team
 *
 * @author ClousClouds Team
 * @link https://xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\phpstan\rules;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use pocketmine\utils\LegacyEnumShimTrait;
use function sprintf;

/**
 * @phpstan-implements Rule<StaticCall>
 */
final class DeprecatedLegacyEnumAccessRule implements Rule{

	public function getNodeType() : string{
		return StaticCall::class;
	}

	public function processNode(Node $node, Scope $scope) : array{
		/** @var StaticCall $node */
		if(!$node->name instanceof Node\Identifier){
			return [];
		}
		$caseName = $node->name->name;
		$classType = $node->class instanceof Node\Name ?
			$scope->resolveTypeByName($node->class) :
			$scope->getType($node->class);

		$errors = [];
		$reflections = $classType->getObjectClassReflections();
		foreach($reflections as $reflection){
			if(!$reflection->hasTraitUse(LegacyEnumShimTrait::class) || !$reflection->implementsInterface(\UnitEnum::class)){
				continue;
			}

			if(!$reflection->hasNativeMethod($caseName)){
				$errors[] = RuleErrorBuilder::message(sprintf(
					'Use of legacy enum case accessor %s::%s().',
					$reflection->getName(),
					$caseName
				))->tip(sprintf(
					'Access the enum constant directly instead (remove the brackets), e.g. %s::%s',
					$reflection->getName(),
					$caseName
				))->identifier('pocketmine.enum.deprecatedAccessor')
					->build();
			}
		}

		return $errors;
	}
}
