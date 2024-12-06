p<?php

require_once __DIR__ . '/../vendor/autoload.php';

$jsonInputFile = __DIR__ . '/../vendor/xpocketmp/bedrock-data/required_item_list.json';
$outputFile = __DIR__ . '/../src/block/BlocktoItemTypeIds.php';

if (!file_exists($jsonInputFile)) {
	die("File $jsonInputFile tidak ditemukan.\n");
	exit();
}

$jsonData = file_get_contents($jsonInputFile);
$requiredItemData = json_decode($jsonData, true);

if ($requiredItemData === null) {
	die("Gagal menguraikan file JSON.\n");
	exit(1);
}

$output = "<?php\n\nnamespace xpocketmp\\block;\n\nclass BlocktoItemTypeIds {\n";

foreach ($requiredItemData as $block => $itemType) {
	$constName = strtoupper(str_replace(' ', '_', $block));
	$output .= "    public const {$constName} = 'minecraft:{$itemType}';\n";
	$output .= "    public const MAIN = 'minecraft:main';\n";
}

$output .= "}\n";

file_put_contents($outputFile, $output);

echo "File BlocktoItemTyeIds.php berhasil dibuat di $outputFile.\n";