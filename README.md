## Features/Changes
- Support for v1.19
- Server will not crash from errors (unless a crashloop occurs)
- Removed internal server error
- Compatible with plugins using `ItemBlock::write()` methods

## How to get compiled version?
- Get a compiled `.phar` version from [here](https://github.com/ownagepe/PocketMine-MP/releases/tag/1.18.30)

## Why would you make these changes?
- Production servers can't be frequently dealing with server crashes as that will affect the playerbase, so instead we prevent the crash from happening and log it to patch later
- Internal Server Error irritates players so that's changed to a message rather than a kick. It logs via a webhook to be patched later
- Added Compatibility with older plugins that used to break when using `ItemBlock::write()`
- __If for whatever godforsaken reason you want to use this be aware that server crashes & internal server error kicks happen for a reason and disregarding them could lead to server data corruption__


<p align="center">
	<a href="https://pmmp.io"><img src="http://cdn.pocketmine.net/img/PocketMine-MP-h.png"></img></a><br>
	<b>A highly customisable, open source server software for Minecraft: Bedrock Edition written in PHP</b>
</p>

<p align="center">
	<img src="https://github.com/pmmp/PocketMine-MP/workflows/CI/badge.svg" alt="CI" />
	<a href="https://github.com/pmmp/PocketMine-MP/releases"><img src="https://img.shields.io/github/v/tag/pmmp/PocketMine-MP?label=release&logo=github" alt="GitHub tag (latest semver)" /></a>
	<a href="https://hub.docker.com/r/pmmp/pocketmine-mp"><img src="https://img.shields.io/docker/v/pmmp/pocketmine-mp?logo=docker&label=image" alt="Docker image version (latest semver)" /></a>
	<a href="https://discord.gg/bmSAZBG"><img src="https://img.shields.io/discord/373199722573201408?label=discord&color=7289DA&logo=discord" alt="Discord" /></a>
</p>

## Getting started
- [Documentation](http://pmmp.readthedocs.org/)
- [Installation instructions](https://pmmp.readthedocs.io/en/rtfd/installation.html)
- [Docker image](https://hub.docker.com/r/pmmp/pocketmine-mp)
- [Plugin repository](https://poggit.pmmp.io/plugins)

## Discussion/Help
- [Forums](https://forums.pmmp.io/)
- [Discord](https://discord.gg/bmSAZBG)
- [StackOverflow](https://stackoverflow.com/tags/pocketmine)

## For developers
 * [Building and running from source](BUILDING.md)
 * [Developer documentation](https://devdoc.pmmp.io) - General documentation for PocketMine-MP plugin developers
 * [Latest API documentation](https://jenkins.pmmp.io/job/PocketMine-MP-doc/doxygen/) - Doxygen documentation generated from development
 * [DevTools](https://github.com/pmmp/DevTools/) - Development tools plugin for creating plugins
 * [ExamplePlugin](https://github.com/pmmp/ExamplePlugin/) - Example plugin demonstrating some basic API features
 * [Contributing Guidelines](CONTRIBUTING.md)

## Donate
- Bitcoin Cash (BCH): `qq3r46hn6ljnhnqnfwxt5pg3g447eq9jhvw5ddfear`
- Bitcoin (BTC): `171u8K9e4FtU6j3e5sqNoxKUgEw9qWQdRV`
- Stellar Lumens (XLM): `GAAC5WZ33HCTE3BFJFZJXONMEIBNHFLBXM2HJVAZHXXPYA3HP5XPPS7T`
- [Patreon](https://www.patreon.com/pocketminemp)

## Licensing information
This project is licensed under LGPL-3.0. Please see the [LICENSE](/LICENSE) file for details.

pmmp/PocketMine are not affiliated with Mojang. All brands and trademarks belong to their respective owners. PocketMine-MP is not a Mojang-approved software, nor is it associated with Mojang.
