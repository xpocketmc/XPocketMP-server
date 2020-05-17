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
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

// This code is based on a Go implementation and its license is below:
// Copyright (c) 2010 Jack Palevich. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions are
// met:
//
//    * Redistributions of source code must retain the above copyright
// notice, this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above
// copyright notice, this list of conditions and the following disclaimer
// in the documentation and/or other materials provided with the
// distribution.
//    * Neither the name of Google Inc. nor the names of its
// contributors may be used to endorse or promote products derived from
// this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
// A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
// OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
// LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
// DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
// THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
// (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
// OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

declare(strict_types=1);

/**
 * UPnP port forwarding support.
 */
namespace pocketmine\network\upnp;

use pocketmine\utils\Internet;
use SimpleXMLElement;
use function socket_create;
use function socket_set_option;
use function socket_recvfrom;
use function socket_sendto;
use function trim;
use function strlen;
use function preg_match;
use function parse_url;
use const AF_INET;
use const SOCK_DGRAM;
use const SOL_UDP;
use const IPPROTO_IP;
use const IP_MULTICAST_IF;
use const IP_MULTICAST_LOOP;
use const IP_MULTICAST_TTL;
use const SOL_SOCKET;
use const SO_RCVTIMEO;

abstract class UPnP{
	/** @var string|null */
	private static $serviceURL;
	
	public static function getControlUrl() : string{
		try{
			$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
			socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ["sec" => 3, "usec" => 0]);
			$contents =
				"M-SEARCH * HTTP/1.1\r\n" .
				"MX: 2\r\n" .
				"HOST: 239.255.255.250:1900\r\n" .
				"MAN: \"ssdp:discover\"\r\n" .
				"ST: upnp:rootdevice\r\n\r\n";
			socket_sendto($socket, $contents, strlen($contents), 0, "239.255.255.250", 1900);
			socket_recvfrom($socket, $buffer, 1024, 0, $responseHost, $responsePort);
			socket_close($socket);
		}catch(\Exception $e){
			throw new \RuntimeException("Socket error: " . $e->getMessage());
		}
		if($buffer === false || !preg_match('/location\s*:\s*(.+)\n/i', $buffer, $matches)){
			throw new \RuntimeException("Unable to find the router. Ensure that network discovery is enabled in Control Panel.");
		}
		$location = trim($matches[1]);
		$url = parse_url($location);
		if($url === false){
			throw new \RuntimeException("Failed to parse the router's url: {$location}");
		}
		$response = Internet::getURL($location, 3, [], $err, $headers, $httpCode);
		if($response === false){
			throw new \RuntimeException("Unable to access XML: {$err}");
		}
		if($httpCode !== 200){
			throw new \RuntimeException("Unable to access XML: {$response}");
		}

		$defaultInternalError = libxml_use_internal_errors(true);
		try{
			$root = new \SimpleXMLElement($response);
		}catch(\Exception $e){
			throw new \RuntimeException("Broken XML.");
		}
		libxml_use_internal_errors($defaultInternalError);
		
		$xpathResult = $root->xpath(
			'//device[deviceType="urn:schemas-upnp-org:device:InternetGatewayDevice:1"]' .
			'/deviceList/device[deviceType="urn:schemas-upnp-org:device:WANDevice:1"]' .
			'/deviceList/device[deviceType="urn:schemas-upnp-org:device:WANConnectionDevice:1"]' .
			'/serviceList/service[serviceType="urn:schemas-upnp-org:service:WANIPConnection:1"]' .
			'/controlURL'
		);
		if($xpathResult === false || count($xpathResult) === 0){
			// if result is false or [], i.e. error or notfound
			throw new \RuntimeException("Your router does not support portforwarding");
		}
		$controlURL = (string)($xpathResult[0]);
		$serviceURL = "{$url['host']}:{$url['port']}/{$controlURL}";
		return $serviceURL;
	}

	public static function PortForward(int $port) : void{
		if(!Internet::$online){
			throw new \RuntimeException("Server is offline");
		}

		if(!isset(self::$serviceURL)){
			self::$serviceURL = self::getControlUrl();
		}
		$body =
			'<u:AddPortMapping xmlns:u="urn:schemas-upnp-org:service:WANIPConnection:1">' .
				'<NewRemoteHost></NewRemoteHost>' .
				'<NewExternalPort>' . $port . '</NewExternalPort>' .
				'<NewProtocol>UDP</NewProtocol>' .
				'<NewInternalPort>' . $port . '</NewInternalPort>' .
				'<NewInternalClient>' . Internet::getInternalIP() . '</NewInternalClient>' .
				'<NewEnabled>1</NewEnabled>' .
				'<NewPortMappingDescription>PocketMine-MP</NewPortMappingDescription>' .
				'<NewLeaseDuration>0</NewLeaseDuration>' .
			'</u:AddPortMapping>';

		$contents =
			'<?xml version="1.0"?>' .
			'<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">' .
			'<s:Body>' . $body . '</s:Body></s:Envelope>';

		$headers = [
			'Content-Type: text/xml',
			'SOAPAction: "urn:schemas-upnp-org:service:WANIPConnection:1#AddPortMapping"'
		];

		if(!Internet::postURL(self::$serviceURL, $contents, 3, $headers)){
			throw new \RuntimeException("Failed to portforward using UPnP. Ensure that network discovery is enabled in Control Panel.");
		}
	}

	public static function RemovePortForward(int $port) : bool{
		if(!Internet::$online){
			return false;
		}
		if(!isset(self::$serviceURL)){
			return false;
		}

		$body =
			'<u:DeletePortMapping xmlns:u="urn:schemas-upnp-org:service:WANIPConnection:1">' .
				'<NewRemoteHost></NewRemoteHost>' .
				'<NewExternalPort>' . $port . '</NewExternalPort>' .
				'<NewProtocol>UDP</NewProtocol>' .
			'</u:DeletePortMapping>';

		$contents =
			'<?xml version="1.0"?>' .
			'<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">' .
			'<s:Body>' . $body . '</s:Body></s:Envelope>';
		
		$headers = [
			'Content-Type: text/xml',
			'SOAPAction: "urn:schemas-upnp-org:service:WANIPConnection:1#DeletePortMapping"'
		];

		if(!Internet::postURL(self::$serviceURL, $contents, 3, $headers)){
			return false;
		}

		return true;
	}
}
