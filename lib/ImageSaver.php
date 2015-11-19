<?php

namespace image\download;

/**
 * Class for saving image file to file system
 *
 * Class ImageSaver
 * @package image\download
 */
class ImageSaver{
    /**
     * Directory for saving image file
     *
     * @var string
     */
    private $dir = '';

    /**
     * Creates new ImageSaver and sets directory $dir for save. For ex.: new ImageSaver('var/www/html/project_root/writable_directory')
     *
     * @param string $dir
     */
    function __construct($dir = ''){
        $this->setDir($dir);
    }

    /**
     * Gets directory @see $dir for saving
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Sets directory @see $dir for saving
     *
     * @param string $dir
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
    }

    /**
     * Checks directory existing. If directory not exists that creates directory @see $dir
     *
     * @return bool
     */
    protected function resolveDir(){
        if (!file_exists($this->dir)) {
            return mkdir($this->dir, 0777, true);
        }

        return true;
    }

    /**
     * Checks @see $dir field and if it not empty than try to resolve it
     *
     * @return bool
     */
    protected function checkDir(){
        if(empty($this->dir)){
            return false;
        }
        return $this->resolveDir();
    }


    /**
     * Saves file which is available by @see ImageInstance::$imageUrl into @see $dir
     *
     * @param ImageInstance $imageInstance
     * @return int
     * @throws \Exception
     */
    public function save(ImageInstance $imageInstance){
        if(!$this->checkDir()){
            throw new \Exception('Wrong directory!');
        }

        $fileName = $imageInstance->getImageName();
        $fileFullName = $this->dir.DIRECTORY_SEPARATOR.$fileName;
        $imageUrl = $imageInstance->getImageUrl();

        return $this->createFile($fileFullName, $imageUrl);
    }

    /**
     * Creates file @param $fileFullName
     * from content of @param $imageUrl
     *
     * @return int - file size in bytes
     */
    protected function createFile($fileFullName, $imageUrl){
        return file_put_contents($fileFullName, file_get_contents($imageUrl));
    }
}