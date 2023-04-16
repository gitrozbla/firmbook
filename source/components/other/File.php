<?php

Yii::import('ext.cfile.CFile');

/**
 * Pliki użytkownika.
 *
 * Ułatwia bezpieczne dodawanie i usuwanie plików przypisanych do obiektów.
 * Inteligentnie zarządza folderami.
 * Rozszerza funkcjonalności CFile
 * @see http://www.yiiframework.com/extension/cfile/
 * 
 * @category components
 * @package components\other
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class File extends CFile 
{
    /**
     * Ścieżka do plików.
     * @var string
     */
    public $filesPath = '';
    
    /**
     * Ścieżka absolutna.
     * @var string
     */
    protected static $_realFilesPath = null;
    /**
     * Czy folder jest pusty.
     * @var bool
     */
    protected $_isDirEmpty = null;
    
    /**
     * Inicjuje komponent File, ustawiając ścieżki.
     */
    public function init() {
        // normalize filesPath (for comparing realPaths)
        $pathObject = $this->set($this->filesPath);
        // full path
        $path = $pathObject->getRealPath();
        
        self::$_realFilesPath = $path;
        // cwd
        chdir($path);
    }
    
    /**
     * Tworzy instancję.
     * @param string $filepath Ścieżka do pliku.
     * @param string $class_name Klasa instancji.
     * @return object
     */
    public static function getInstance($filepath, $class_name=__CLASS__) {
        return parent::getInstance($filepath, $class_name=__CLASS__);
    }
    
    /**
     * Kopiuje plik.
     * Dodane automatyczne tworzenie folderów.
     * @param string $dest Ścieżka docelowa. Musi być relatywna!
     * @param bool $createDirs Automatyczne tworzenie folderów.
     * @return object|bool Instancja lub false.
     */
    public function copy($relativeDest, $createDirs=true) {
        
        // separator fix
        $relativeDest = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $relativeDest);
        
        // get real destination path (relative to source path)
        $absoluteDesc = $this->getDirname() . DIRECTORY_SEPARATOR . $relativeDest;
        
        if ($createDirs) {
            $dirname = dirname($absoluteDesc);
            if ($dirname != '') {
                $dir = $this->set($dirname);
                if ($dir->isDir === false) {
                    $this->createDir($this->permissions, $dirname);
                }
            }
        }
        
        return parent::copy($absoluteDesc);
    }
    
    /**
     * Tworzy nowe foldery.
     * Dodana obsługa tworzenia całej ścieżki.
     * @param int $permissions Uprawnienia nowych folderów.
     * @param string $directory Ścieżka folderu.
     * @return object|bool Instancja lub false lub true (brak instancji).
     */
    public function createDir($permissions=0755, $directory=null) {
        if (!empty($directory) and $directory != '.') {
            $dir = $this->set($directory);
            if ($dir->isDir === false) {
                $this->createDir($permissions, dirname($directory));
                return parent::createDir($permissions, $directory);
            }
            return false;
        }
        
        return parent::createDir($permissions);
    }
    
    /**
     * Usuwa foldery.
     * Dodana obsługa usuwania kaskadowego.
     * @param bool $purge Czy usunąć wraz z zawartością.
     * @param bool $deleteEmptyDirs Czy usunąć puste foldery na ścieżce.
     * @return bool Rezultat.
     */
    public function delete($purge=True, $deleteEmptyDirs=true) {
        $result = parent::delete($purge);
        
        $dirname = $this->dirname;
        if ($deleteEmptyDirs == true) {
            $this->deleteEmptyDirs($dirname);
        }
        return $result;
    }
    
    /**
     * Usuwa puste foldery na ścieżce.
     * @param string $lastDir Ścieżka do najdalszego folderu.
     */
    protected function deleteEmptyDirs($lastDir=null) {
        if ($lastDir !== null) {
            $dir = $this->set($lastDir);
        } else {
            $dir = $this;
        }
        $nextDirname = $dir->dirname;
        
        if ($dir->isDirEmpty === true) {
            $dir->delete(false, false);
            if ($nextDirname != self::$_realFilesPath and !empty($nextDirname)) {
                $dir = $dir->set($nextDirname);
                $dir->deleteEmptyDirs();
            }
        }
    }
    
    /**
     * Sprawdze czy folder jest pusty.
     * Zapamiętuje wynik.
     * @param string $directory Ścieżka do folderu.
     * @return bool Rezultat.
     */
    public function getIsDirEmpty($directory = null) {
        if ($directory === null) {
            // CACHING DISABLED!
            // because directory content could be  
            // modified removed several times
            if (/*!isset($this->_isDirEmpty)*/ true) {
                if ($this->getIsDir()) {
                    $realPath = $this->getRealPath();
                    $this->_isDirEmpty = $this->_getIsDirEmpty($realPath);
                } else {
                    $this->_isDirEmpty = False;
                }
            }
            
            return $this->_isDirEmpty;
        } else {
            return $this->_getIsDirEmpty($directory);
        }
    }
    
    // isDir recreate safe - quick fix
    public function getIsDir()
    {
        return is_dir($this->getRealPath());
    }
    
    /**
     * Funkcja pomocnicza przy sprawdzaniu czy folder jest pusty.
     * @param string $dir Ścieżka do folderu
     * @return boolean|null Wynik lub null w przypadku braku dostępu.
     */
    protected function _getIsDirEmpty($dir) {
        if (!is_readable($dir)) return NULL; 
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                return FALSE;
            }
        }
        return TRUE;
    }
    
    /**
     * Tworzy plik.
     * Dodana możliwość tworzenia brakujących folderów.
     * @param bol $createDirs Czy tworzyć brakujące foldery.
     * @return object|bool Instancja lub wynik operacji, jeśli instancja nie istnieje.
     */
    public function create($createDirs=true) {
        // get real destination path (relative to source path)
        $realDest = $this->getRealPath();
        
        if ($createDirs) {
            $dirname = dirname($realDest);
            if ($dirname != '') {
                $dir = $this->set($dirname);
                if ($dir->isDir === false) {
                    $this->createDir(0755, $dirname);
                }
            }
        }
        
        return parent::create();
    }
    
}
