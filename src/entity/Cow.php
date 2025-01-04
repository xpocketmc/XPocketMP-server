<?php

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\entity\animation\ArmSwingAnimation;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\block\VanillaBlocks;
use function mt_rand;

class Cow extends Living{

	private bool $isBaby = false;
	private int $growUpTime = 6000;

	public static function getNetworkTypeId() : string{
		return EntityIds::COW;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return $this->isBaby 
			? new EntitySizeInfo(0.7, 0.45) 
			: new EntitySizeInfo(1.4, 0.9); 
	}

	public function getName() : string{
		return "Cow";
	}

	public function getDrops() : array{
		if($this->isBaby){
			return [];
		}

		return [
			VanillaItems::RAW_BEEF()->setCount(mt_rand(1, 3)),
			VanillaItems::LEATHER()->setCount(mt_rand(0, 2))
		];
	}

	public function getXpDropAmount() : int{
		return mt_rand(1, 3);
	}

	public function onInteract(Player $player, Vector3 $clickPos): bool{
		$item = $player->getInventory()->getItemInHand();

		if($item->equals(VanillaItems::BUCKET(), true)){
			$player->getInventory()->addItem(VanillaItems::MILK_BUCKET());
			return true;
		}

		if($item->equals(VanillaItems::WHEAT(), true) && $this->isBaby){
			$this->growUp();
			return true;
		}

		return parent::onInteract($player, $clickPos);
	}

	public function onUpdate(int $currentTick) : bool{
		$nearestPlayer = $this->getNearestPlayer();

		if($nearestPlayer !== null){
			$distance = $this->getPosition()->distance($nearestPlayer->getPosition());

			if($distance <= 6 && $nearestPlayer->getInventory()->contains(VanillaItems::WHEAT())){
				$this->moveTowards($nearestPlayer->getPosition());
			}
		}

		if($this->isBaby && $currentTick % 20 === 0){
			$this->growUpTime--;
			if($this->growUpTime <= 0){
				$this->growUp();
			}
		}

		return parent::onUpdate($currentTick);
	}

	private function getNearestPlayer() : ?Player{
		$nearestPlayer = null;
		$nearestDistance = PHP_FLOAT_MAX;

		foreach($this->getWorld()->getPlayers() as $player){
			$distance = $this->getPosition()->distance($player->getPosition());
			if($distance < $nearestDistance){
				$nearestPlayer = $player;
				$nearestDistance = $distance;
			}
		}

		return $nearestPlayer;
	}

	private function moveTowards(Vector3 $target) : void{
		$direction = new Vector3(
			$target->x - $this->getPosition()->x,
			$target->y - $this->getPosition()->y,
			$target->z - $this->getPosition()->z
		);
		$direction = $direction->normalize();
		$this->motion->x = $direction->x * 0.2;
		$this->motion->z = $direction->z * 0.2;
	}

	private function growUp() : void{
		$this->isBaby = false;
		$this->setSize(new EntitySizeInfo(1.4, 0.9));
	}
}
