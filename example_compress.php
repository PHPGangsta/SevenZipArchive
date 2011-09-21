<?php

require_once 'SevenZipArchive.php';
$sevenZipArchive = new SevenZipArchive();
$sevenZipArchive->setExecutablePath('C:/Program Files (x86)/7-Zip/7za.exe')
        ->setArchivePath('C:/Temp/test.tar')
        ->setFormat(SevenZipArchive::FORMAT_TAR)
        ->setFilePath('C:/Temp/Angeln.gif');

if ($sevenZipArchive->compress()) {
    echo 'compression successful';
} else {
    echo 'compression failed';
}