<?php

require_once __DIR__ . '/../vendor/autoload.php';

$jsonInputFile = __DIR__ . '/../vendor/pocketmine/bedrock-data/required_item_list.json';
$outputFile = __DIR__ . '/../src/block/BlocktoItemTypeIds.php';

if (!file_exists($jsonInputFile)) {
    die("File $jsonInputFile tidak ditemukan.\n");
}

$jsonData = file_get_contents($jsonInputFile);
$requiredItemData = json_decode($jsonData, true);

if ($requiredItemData === null) {
    die("Gagal menguraikan file JSON.\n");
}

$output = "<?php\n\nnamespace pocketmine\\block;\n\nclass BlocktoItemTypeIds {\n";

foreach ($requiredItemData as $block => $itemType) {
    $constName = strtoupper(str_replace(' ', '_', $block)); // Convert block name to uppercase with underscores
    $output .= "    public const {$constName} = 'minecraft:{$itemType}';\n";
}

$output .= "}\n";

file_put_contents($outputFile, $output);

echo "File BlocktoItemTyeIds.php berhasil dibuat di $outputFile.\n";
