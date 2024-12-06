<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author XPocketMP Team
 * @link http://www.xpocketmc.xyz/
 *
 *
 */

declare(strict_types=1);

namespace XPocketMP\permission;

final class DefaultPermissionNames{
	public const BROADCAST_ADMIN = "XPocketMP.broadcast.admin";
	public const BROADCAST_USER = "XPocketMP.broadcast.user";
	public const COMMAND_BAN_IP = "XPocketMP.command.ban.ip";
	public const COMMAND_BAN_LIST = "XPocketMP.command.ban.list";
	public const COMMAND_BAN_PLAYER = "XPocketMP.command.ban.player";
	public const COMMAND_CLEAR_OTHER = "XPocketMP.command.clear.other";
	public const COMMAND_CLEAR_SELF = "XPocketMP.command.clear.self";
	public const COMMAND_DEFAULTGAMEMODE = "XPocketMP.command.defaultgamemode";
	public const COMMAND_DIFFICULTY = "XPocketMP.command.difficulty";
	public const COMMAND_DUMPMEMORY = "XPocketMP.command.dumpmemory";
	public const COMMAND_EFFECT_OTHER = "XPocketMP.command.effect.other";
	public const COMMAND_EFFECT_SELF = "XPocketMP.command.effect.self";
	public const COMMAND_ENCHANT_OTHER = "XPocketMP.command.enchant.other";
	public const COMMAND_ENCHANT_SELF = "XPocketMP.command.enchant.self";
	public const COMMAND_GAMEMODE_OTHER = "XPocketMP.command.gamemode.other";
	public const COMMAND_GAMEMODE_SELF = "XPocketMP.command.gamemode.self";
	public const COMMAND_GC = "XPocketMP.command.gc";
	public const COMMAND_GIVE_OTHER = "XPocketMP.command.give.other";
	public const COMMAND_GIVE_SELF = "XPocketMP.command.give.self";
	public const COMMAND_HELP = "XPocketMP.command.help";
	public const COMMAND_KICK = "XPocketMP.command.kick";
	public const COMMAND_KILL_OTHER = "XPocketMP.command.kill.other";
	public const COMMAND_KILL_SELF = "XPocketMP.command.kill.self";
	public const COMMAND_LIST = "XPocketMP.command.list";
	public const COMMAND_ME = "XPocketMP.command.me";
	public const COMMAND_OP_GIVE = "XPocketMP.command.op.give";
	public const COMMAND_OP_TAKE = "XPocketMP.command.op.take";
	public const COMMAND_PARTICLE = "XPocketMP.command.particle";
	public const COMMAND_PLUGINS = "XPocketMP.command.plugins";
	public const COMMAND_SAVE_DISABLE = "XPocketMP.command.save.disable";
	public const COMMAND_SAVE_ENABLE = "XPocketMP.command.save.enable";
	public const COMMAND_SAVE_PERFORM = "XPocketMP.command.save.perform";
	public const COMMAND_SAY = "XPocketMP.command.say";
	public const COMMAND_SEED = "XPocketMP.command.seed";
	public const COMMAND_SETWORLDSPAWN = "XPocketMP.command.setworldspawn";
	public const COMMAND_SPAWNPOINT_OTHER = "XPocketMP.command.spawnpoint.other";
	public const COMMAND_SPAWNPOINT_SELF = "XPocketMP.command.spawnpoint.self";
	public const COMMAND_STATUS = "XPocketMP.command.status";
	public const COMMAND_STOP = "XPocketMP.command.stop";
	public const COMMAND_TELEPORT_OTHER = "XPocketMP.command.teleport.other";
	public const COMMAND_TELEPORT_SELF = "XPocketMP.command.teleport.self";
	public const COMMAND_TELL = "XPocketMP.command.tell";
	public const COMMAND_TIME_ADD = "XPocketMP.command.time.add";
	public const COMMAND_TIME_QUERY = "XPocketMP.command.time.query";
	public const COMMAND_TIME_SET = "XPocketMP.command.time.set";
	public const COMMAND_TIME_START = "XPocketMP.command.time.start";
	public const COMMAND_TIME_STOP = "XPocketMP.command.time.stop";
	public const COMMAND_TIMINGS = "XPocketMP.command.timings";
	public const COMMAND_TITLE_OTHER = "XPocketMP.command.title.other";
	public const COMMAND_TITLE_SELF = "XPocketMP.command.title.self";
	public const COMMAND_TRANSFERSERVER = "XPocketMP.command.transferserver";
	public const COMMAND_UNBAN_IP = "XPocketMP.command.unban.ip";
	public const COMMAND_UNBAN_PLAYER = "XPocketMP.command.unban.player";
	public const COMMAND_VERSION = "XPocketMP.command.version";
	public const COMMAND_WHITELIST_ADD = "XPocketMP.command.whitelist.add";
	public const COMMAND_WHITELIST_DISABLE = "XPocketMP.command.whitelist.disable";
	public const COMMAND_WHITELIST_ENABLE = "XPocketMP.command.whitelist.enable";
	public const COMMAND_WHITELIST_LIST = "XPocketMP.command.whitelist.list";
	public const COMMAND_WHITELIST_RELOAD = "XPocketMP.command.whitelist.reload";
	public const COMMAND_WHITELIST_REMOVE = "XPocketMP.command.whitelist.remove";
	public const GROUP_CONSOLE = "XPocketMP.group.console";
	public const GROUP_OPERATOR = "XPocketMP.group.operator";
	public const GROUP_USER = "XPocketMP.group.user";
}