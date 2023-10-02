<?php

declare(strict_types=1);

namespace Joestarfish\FarmChest;

use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\Crops;
use pocketmine\block\NetherWartPlant;
use pocketmine\block\SweetBerryBush;
use pocketmine\block\tile\Chest as TileChest;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\inventory\transaction\InventoryTransaction;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\inventory\transaction\action\SlotChangeAction;

class Main extends PluginBase implements Listener {
	private Config $config;

	public function onEnable(): void {
		$this->config = $this->getConfig();
		$this->getServer()
			->getPluginManager()
			->registerEvents($this, $this);
	}

	public function onInteract(PlayerInteractEvent $event) {
		if ($event->getAction() != PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
			return;
		}

		if ($event->getBlock()->getTypeId() != BlockTypeIds::TRAPPED_CHEST) {
			return;
		}

		$position = $event->getBlock()->getPosition();
		$world = $position->getWorld();
		$tile = $world->getTile($position);

		if (!$tile instanceof TileChest) {
			return;
		}

		$inventory = $tile->getInventory();

		$leftover_drops = [];

		$max_x = (int) abs($this->config->getNested('range.x-axis', 5));
		// We could add the Y axis
		$max_z = (int) abs($this->config->getNested('range.z-axis', 5));

		for ($x = -$max_x; $x < $max_x; $x++) {
			for ($z = -$max_z; $z < $max_z; $z++) {
				$target = $world->getBlockAt(
					$position->getFloorX() + $x,
					$position->getFloorY(),
					$position->getFloorZ() + $z,
				);

				if (!$this->isValidBlock($target)) {
					continue;
				}

				$drops = $this->getDrops($target);

				if (count($drops) == 0) {
					continue;
				}

				$leftover_drops = array_merge(
					$leftover_drops,
					$inventory->addItem(...$drops),
				);
			}
		}

		// Without a transaction, hopper plugins might not notice that new items were added to the chest
		// This isn't actually what happens but i don't think it will cause issues
		// If it does, feel free to open an issue or a PR at https://github.com/Joestarfish/FarmChest/issues
		$transaction = new InventoryTransaction($event->getPlayer(), [
			new SlotChangeAction(
				$inventory,
				0,
				$inventory->getItem(0),
				$inventory->getItem(0),
			),
		]);
		$ev = new InventoryTransactionEvent($transaction);
		$ev->call();

		if (!$this->config->get('should-drop-leftover-items', true)) {
			return;
		}

		foreach ($leftover_drops as $item) {
			$world->dropItem($position, $item);
		}
	}

	private function isValidBlock(Block $block): bool {
		$valid_crops = [
			BlockTypeIds::WHEAT,
			BlockTypeIds::BEETROOTS,
			BlockTypeIds::POTATOES,
			BlockTypeIds::CARROTS,
			BlockTypeIds::PUMPKIN,
			BlockTypeIds::MELON,
			BlockTypeIds::SWEET_BERRY_BUSH,
			BlockTypeIds::NETHER_WART,
		];

		if (!in_array($block->getTypeId(), $valid_crops)) {
			return false;
		}

		if (
			($block instanceof Crops || $block instanceof NetherWartPlant) &&
			$block->getAge() < $block::MAX_AGE
		) {
			return false;
		}

		if (
			$block instanceof SweetBerryBush &&
			$block->getAge() < $block::STAGE_BUSH_SOME_BERRIES
		) {
			return false;
		}

		return true;
	}

	/**
	 * @return Item[]
	 */
	private function getDrops(Block $block): array {
		$position = $block->getPosition();
		$world = $position->getWorld();

		if ($block instanceof SweetBerryBush) {
			if (($count = $block->getBerryDropAmount()) < 1) {
				return [];
			}

			$drops = [VanillaItems::SWEET_BERRIES()->setCount($count)];

			$world->setBlockAt(
				$position->getFloorX(),
				$position->getFloorY(),
				$position->getFloorZ(),
				$block->setAge($block::STAGE_BUSH_NO_BERRIES),
			);

			return $drops;
		}

		$drops = $block->getDropsForCompatibleTool(VanillaItems::AIR());

		$world->setBlockAt(
			$position->getFloorX(),
			$position->getFloorY(),
			$position->getFloorZ(),
			$block instanceof Crops || $block instanceof NetherWartPlant
				? $block->setAge(0)
				: VanillaBlocks::AIR(),
		);

		return $drops;
	}
}
