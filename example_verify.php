<?php

require_once 'SevenZipArchive.php';
$sevenZipArchive = new SevenZipArchive();
$sevenZipArchive->setExecutablePath('C:/Program Files (x86)/7-Zip/7za.exe')
        ->setArchivePath('C:/Temp/test.tar');

if ($sevenZipArchive->verify()) {
    echo 'verification successful';
} else {
    echo 'verification failed';
}