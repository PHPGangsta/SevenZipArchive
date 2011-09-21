<?php

require_once 'SevenZipArchive.php';
$sevenZipArchive = new SevenZipArchive();
$sevenZipArchive->setExecutablePath('C:/Program Files (x86)/7-Zip/7za.exe')
        ->setArchivePath('C:/Temp/test.tar')
        ->setFilePath('C:/Temp/C');

if ($sevenZipArchive->decompress()) {
    echo 'decompression successful';
} else {
    echo 'decompression failed';
}