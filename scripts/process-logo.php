<?php

$srcPath = dirname(__DIR__) . '/public/brand/logo.png';

// Re-download original first
$original = file_get_contents('https://asnenafrica.org/img/logo.png');
if ($original === false) {
    fwrite(STDERR, "Could not download original logo\n");
    exit(1);
}
file_put_contents($srcPath, $original);

$src = imagecreatefrompng($srcPath);
if ($src === false) {
    fwrite(STDERR, "Failed to load logo\n");
    exit(1);
}

$w = imagesx($src);
$h = imagesy($src);
$dst = imagecreatetruecolor($w, $h);
imagealphablending($dst, false);
imagesavealpha($dst, true);
$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
imagefill($dst, 0, 0, $transparent);

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgb = imagecolorat($src, $x, $y);
        $a = ($rgb & 0x7F000000) >> 24;
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        // Near-black pixels become fully transparent
        if ($r <= 25 && $g <= 25 && $b <= 25) {
            imagesetpixel($dst, $x, $y, $transparent);
            continue;
        }

        $col = imagecolorallocatealpha($dst, $r, $g, $b, $a);
        imagesetpixel($dst, $x, $y, $col);
    }
}

imagepng($dst, $srcPath);
imagepng($dst, dirname(__DIR__) . '/public/brand/asnen-logo.png');
copy($srcPath, dirname(__DIR__) . '/content-sources/logo.png');
imagedestroy($src);
imagedestroy($dst);

echo "OK {$w}x{$h}\n";
