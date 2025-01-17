<?php
function replaceInFiles($directory, $pattern, $replacement) {
	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
	);

	$changedFiles = []; // Untuk mencatat file yang diubah

	foreach ($files as $file) {
		// Proses hanya file dengan ekstensi .php
		if ($file->getExtension() === 'php') {
			$filePath = $file->getRealPath();

			// Baca isi file
			$content = file_get_contents($filePath);

			// Ganti semua pola yang cocok dengan regex
			$updatedContent = str_replace($pattern, $replacement, $content);

			// Simpan kembali isi file jika ada perubahan
			if ($updatedContent !== null && $updatedContent !== $content) {
				if (file_put_contents($filePath, $updatedContent) !== false) {
					$changedFiles[] = $filePath; // Tambahkan ke daftar file yang diubah
				}
			}
		}
	}

	// Tampilkan daftar file yang diubah
	if (!empty($changedFiles)) {
		echo "Daftar file yang diubah:\n";
		foreach ($changedFiles as $file) {
			echo "- $file\n";
		}
	} else {
		echo "Tidak ada file yang diubah.\n";
	}
}

// Contoh penggunaan
$directory = __DIR__; // Ganti dengan direktori utama Anda

// Regex untuk menangkap pola dengan spasi/tab tidak wajar
$pattern = 'use pocketmine\profiler\T'; // Menangkap bentuk seperti `use pocketmine\task\AsyncTask;`
$replacement = 'use pocketmine\profiler\T'; // Perbaikan yang benar

replaceInFiles($directory, $pattern, $replacement);
