# FarmChest

A PMMP plugin that modifies the Trapped Chest to make it harvest nearby crops

[![](https://poggit.pmmp.io/shield.api/FarmChest)](https://poggit.pmmp.io/p/FarmChest)
[![](https://poggit.pmmp.io/shield.dl.total/FarmChest)](https://poggit.pmmp.io/p/FarmChest)

The following crops will be harvested:

![wheat](https://github.com/ZtechNetwork/MCBVanillaResourcePack/blob/master/textures/items/wheat.png?raw=true)
![beetroot](https://github.com/ZtechNetwork/MCBVanillaResourcePack/blob/master/textures/items/beetroot.png?raw=true)
![potato](https://github.com/ZtechNetwork/MCBVanillaResourcePack/blob/master/textures/items/potato.png?raw=true)
![carrot](https://github.com/ZtechNetwork/MCBVanillaResourcePack/blob/master/textures/items/carrot.png?raw=true)
![pumpkin_face_off](https://github.com/ZtechNetwork/MCBVanillaResourcePack/blob/master/textures/blocks/pumpkin_face_off.png?raw=true)
![melon](https://github.com/ZtechNetwork/MCBVanillaResourcePack/blob/master/textures/items/melon.png?raw=true)
![sweet_berries](https://github.com/ZtechNetwork/MCBVanillaResourcePack/blob/master/textures/items/sweet_berries.png?raw=true)
![nether_wart](https://github.com/ZtechNetwork/MCBVanillaResourcePack/blob/master/textures/items/nether_wart.png?raw=true)

<details>
	<ul>
		<li>Wheat</li>
		<li>Beetroots</li>
		<li>Potatoes</li>
		<li>Carrots</li>
		<li>Pumpkin</li>
		<li>Melon</li>
		<li>Sweet berries</li>
		<li>Nether Wart</li>
	</ul>
</details>

# Usage

To use the FarmChest, you can simply right click on it and it will harvest crops in a specified zone.

You can also sneak while clicking on the chest to make it harvest and keeping it closed

You could also use a plugin such as [VanillaHopper](https://poggit.pmmp.io/p/VanillaHopper/) that implements hopper. They will pick items inside of the chest and move them around your farm

# Configuration

Inside of the `plugin_data/FarmChest/config.yml` file, you may change the following:

-   **range** - The range at which the FarmChest will harvest crops
    -   **x-axis** - The number of blocks that will be checked on the X axis
    -   **z-axis** - The number of blocks that will be checked on the Z axis
-   **should-drop-leftover-items** - Should the FarmChest drop the items that weren't able to fit inside of it ?
