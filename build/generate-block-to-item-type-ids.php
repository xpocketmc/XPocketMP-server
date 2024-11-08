<?php

use pocketmine\nbt\NbtDataException;
use pocketmine\nbt\NbtFormatException;
use pocketmine\nbt\BigEndianNbtSerializer;
use pocketmine\nbt\tag\CompoundTag;

require_once __DIR__ . '/../vendor/autoload.php'; // Pastikan autoload disesuaikan dengan struktur proyek

// Path file input dan output
$jsonInputFile = __DIR__ . '/../vendor/pocketmine/bedrock-data/required_item_list.json';
$outputFile = __DIR__ . '/../src/block/BlocktoItemTypeIds.php';

// Periksa apakah file JSON ada
if (!file_exists($jsonInputFile)) {
    die("File $jsonInputFile tidak ditemukan.\n");
}

// Baca isi file JSON
$jsonData = file_get_contents($jsonInputFile);
$requiredItemData = json_decode($jsonData, true);

// Periksa apakah data JSON berhasil di-decode
if ($requiredItemData === null) {
    die("Gagal menguraikan file JSON.\n");
	exit(1);
}

// Mulai menulis ke file output
$output = "<?php\n\n";
$output .= "namespace src\\block;\n\n";
$output .= "class BlocktoItemTypeIds {\n";
$output .= "    public const REQUIRED_ITEMS = [\n";

// Iterasi setiap item dalam JSON dan tambahkan ke array
foreach ($requiredItemData as $block => $itemType) {
    $output .= "        '$block' => '$itemType',\n";
}

$output .= "    ];\n";
$output .= "}\n";

// Tulis hasil ke file
file_put_contents($outputFile, $output);

echo "File BlocktoItemTypeIds.php berhasil dibuat di $outputFile.\n";
