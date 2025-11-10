<?php
// Pastikan session dimulai, karena kita akan menyimpan kode di sini
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Buat string acak 5 digit
// Menghilangkan huruf/angka yang mirip (O, 0, I, l, 1)
$text = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 5);

// 2. Simpan kode ke session untuk validasi nanti
$_SESSION['captcha_code'] = $text;

// 3. Atur header agar browser tahu ini adalah gambar
header('Content-type: image/png');

// 4. Buat kanvas gambar
$width = 120;
$height = 40;
$image = imagecreatetruecolor($width, $height);

// 5. Siapkan warna
$bg_color = imagecolorallocate($image, 230, 230, 230); // Latar belakang abu-abu muda
$text_color = imagecolorallocate($image, 50, 50, 50);   // Teks abu-abu tua
$noise_color = imagecolorallocate($image, 150, 150, 150); // Warna noise (garis/titik)

// 6. Isi latar belakang
imagefill($image, 0, 0, $bg_color);

// 7. Tambah noise (garis acak)
for ($i = 0; $i < 5; $i++) {
    imageline($image, 0, rand(0, $height), $width, rand(0, $height), $noise_color);
}

// 8. Tambah noise (titik acak)
for ($i = 0; $i < 500; $i++) {
    imagesetpixel($image, rand(0, $width), rand(0, $height), $noise_color);
}

// 9. Tulis teks ke gambar (menggunakan font bawaan PHP)
$font_size = 5; // Ukuran font bawaan (1-5)
$x = ($width - (imagefontwidth($font_size) * strlen($text))) / 2;
$y = ($height - imagefontheight($font_size)) / 2;
imagestring($image, $font_size, $x, $y, $text, $text_color);

// 10. Output gambar sebagai PNG
imagepng($image);

// 11. Bersihkan memori
imagedestroy($image);
?>