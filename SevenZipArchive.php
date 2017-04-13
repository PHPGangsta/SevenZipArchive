<?php

/**
 * This class helps working with the command line version of 7zip (http://www.7-zip.org) to
 * compress, decompress and verify archives of different formats.
 *
 * @author Michael Kliewe
 * @author Robert Gnuschke, Alpha Team Systems & Consulting GmbH
 * @copyright Michael Kliewe
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link http://www.phpgangsta.de
 * @link http://www.rgshops.de
 */

class SevenZipArchive
{
    // formats for compression and decompression
    const FORMAT_7ZIP  = '7z';
    const FORMAT_XZ    = 'xz';
    const FORMAT_BZIP2 = 'bzip2';
    const FORMAT_GZIP  = 'gzip';
    const FORMAT_TAR   = 'tar';
    const FORMAT_ZIP   = 'zip';
    const FORMAT_WIM   = 'wim';

    // formats only for decompression
    const FORMAT_ARJ      = 'arj';
    const FORMAT_CAB      = 'cab';
    const FORMAT_CHM      = 'chm';
    const FORMAT_CPIO     = 'cpio';
    const FORMAT_CRAM_FS  = 'cramfs';
    const FORMAT_DEB      = 'deb';
    const FORMAT_DMG      = 'dmg';
    const FORMAT_FAT      = 'fat';
    const FORMAT_HFS      = 'hfs';
    const FORMAT_ISO      = 'iso';
    const FORMAT_LZH      = 'lhz';
    const FORMAT_LZMA     = 'lzma';
    const FORMAT_MBR      = 'mbr';
    const FORMAT_MSI      = 'msi';
    const FORMAT_NSIS     = 'nsis';
    const FORMAT_NTFS     = 'ntfs';
    const FORMAT_RAR      = 'rar';
    const FORMAT_RPM      = 'rpm';
    const FORMAT_SQASH_FS = 'sqashfs';
    const FORMAT_UDF      = 'udf';
    const FORMAT_VHD      = 'vhd';
    const FORMAT_XAR      = 'xar';
    const FORMAT_Z        = 'z';

    protected $_executablePath;
    protected $_format;
    protected $_archivePath;
    protected $_filePath;
    protected $_password;
    protected $_debug;

    public function checkExecutable()
    {
        if (!function_exists('shell_exec')) {
            return false;
        }
        $ret = shell_exec($this->_executablePath);
        if (strpos($ret, "7-Zip") === false) {
            return false;
        } else {
            return true;
        }
    }

    public function getDebug()
    {
        return $this->_debug;
    }

    public function setExecutablePath($path)
    {
        $this->_executablePath = $path;
        return $this;
    }

    public function setFormat($format)
    {
        $this->_format = $format;
        return $this;
    }

    public function setArchivePath($path)
    {
        $this->_archivePath = $path;
        return $this;
    }

    public function setFilePath($path)
    {
        $this->_filePath = $path;
        return $this;
    }

    public function setPassword($password)
    {
        $this->_password = $password;
        return $this;
    }

    public function compress()
    {
        $command = '"' . $this->_executablePath . '"'
                 . ' a'
                 . ' -t'.$this->_format
                 . $this->_getPasswordParam()
                 . ' ' . escapeshellarg($this->_archivePath)
                 . ' ' . escapeshellarg($this->_filePath);

        $ret = shell_exec($command);

        if (strpos($ret, 'Everything is Ok')!==false) { // compression was successful
            return true;
        } else {
            $this->_debug = $ret;
            return false;
        }
    }

    public function decompress($withFullPath=false)
    {
        $extractFlag = $withFullPath ? 'x' : 'e';

        $command = '"' . $this->_executablePath . '"'
                 . " {$extractFlag}"
                 . ' -y'
                 . $this->_getPasswordParam()
                 . ' ' . escapeshellarg($this->_archivePath)
                 . ' -o' . escapeshellarg($this->_filePath);

        $ret = shell_exec($command);

        if (strpos($ret, 'Everything is Ok')!==false) {  // decompression was successful
            return true;
        } else {  // password wrong or bad integrity
            $this->_debug = $ret;
            return false;
        }
    }

    public function verify()
    {
        $command = '"' . $this->_executablePath . '"'
                 . ' t'
                 . $this->_getPasswordParam()
                 . ' '.escapeshellarg($this->_archivePath);

        $ret = shell_exec($command);

        if (strpos($ret, 'Everything is Ok')!==false) {  // verification was successful
            return true;
        } else {  // password wrong or bad integrity
            $this->_debug = $ret;
            return false;
        }
    }

    protected function _getPasswordParam()
    {
        $pwd = '';
        if (!empty($this->_password)) {
            $pwd = ' -p' . escapeshellarg($this->_password);
        }
        return $pwd;
    }
}