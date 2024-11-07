<?php

use pocketmine\nbt\NbtDataException;
use pocketmine\nbt\NbtFormatException;
use pocketmine\nbt\BigEndianNbtSerializer;
use pocketmine\nbt\tag\CompoundTag;

require_once __DIR__ . '/../vendor/autoload.php'; // Pastikan autoload disesuaikan dengan struktur proyek

// Path file input dan output
$jsonInputFile = __DIR__ . '/../vendor/pocketmine/bedrock-data/required_item_list.json';
$nbtInputFile = __DIR__ . '/../vendor/pocketmine/bedrock-data/canonical_block_states.nbt';
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
}

// Periksa apakah file NBT ada
if (!file_exists($nbtInputFile)) {
    die("File $nbtInputFile tidak ditemukan.\n");
}

// Fungsi untuk membaca dan mengurai file NBT
function readNbtFile($filePath) {
    try {
        $serializer = new BigEndianNbtSerializer();
        $nbtData = file_get_contents($filePath);
        $rootTag = $serializer->read($nbtData)->getTag();

        if (!$rootTag instanceof CompoundTag) {
            throw new NbtDataException("Data NBT tidak valid: Tag utama bukan CompoundTag.");
        }

        // Ubah CompoundTag menjadi array PHP
        $result = [];
        foreach ($rootTag->getValue() as $blockStateName => $tag) {
            $result[$blockStateName] = $tag->getValue();
        }

        return $result;

    } catch (NbtFormatException | NbtDataException $e) {
        die("Gagal mengurai file NBT: " . $e->getMessage() . "\n");
    }
}

// Membaca data dari file NBT
$canonicalBlockStates = readNbtFile($nbtInputFile);

// Mulai menulis ke file output
$output = "<?php\n\n";
$output .= "namespace src\\block;\n\n";
$output .= "class BlocktoItemTypeIds {\n";
$output .= "    public const REQUIRED_ITEMS = [\n";

// Iterasi setiap item dalam JSON dan tambahkan ke array
foreach ($requiredItemData as $block => $itemType) {
    $output .= "        '$block' => '$itemType',\n";
}

$output .= "    ];\n\n";
$output .= "    public const CANONICAL_BLOCK_STATES = [\n";

// Iterasi setiap blok dalam data NBT yang sudah diuraikan dan tambahkan ke array
foreach ($canonicalBlockStates as $blockState => $properties) {
    $output .= "        '$blockState' => " . var_export($properties, true) . ",\n";
}

$output .= "    ];\n";
$output .= "}\n";

// Tulis hasil ke file
file_put_contents($outputFile, $output);

echo "File BlocktoItemTypeIds.php berhasil dibuat di $outputFile.\n";
