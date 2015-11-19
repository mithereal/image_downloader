<?php

namespace image\download;

/**
 * Class for checking image information
 *
 * Class ImageInstance
 * @package image\download
 */
class ImageInstance{
    /**
     * Downloading image url
     *
     * @var string
     */
    private $imageUrl = '';

    /**
     * Downloading image information
     *
     * @var array
     */
    private $imageInfo = [];

    /**
     * Creates new ImageInstance and sets $imageUrl
     *
     * @param string $imageUrl
     */
    function __construct($imageUrl = ''){
        $this->setImageUrl($imageUrl);
    }

    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param mixed $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * Gets image information array with saving it to the @see imageInfo property
     *
     * @return array
     */
    protected function getImageInfo(){
        if(empty($this->imageInfo)){
            if(!empty($this->imageUrl)) {
                $this->imageInfo = getimagesize($this->imageUrl);
            }
        }

        return $this->imageInfo;
    }

    /**
     * Gets image format code of image information. @see getImageInfo()
     *
     * @return bool
     */
    public function getImageFormatCode(){
        $imageInfo = $this->getImageInfo();
        if(!empty($imageInfo[2])){
            return $imageInfo[2];
        }

        return false;
    }

    /**
     * Checks if file exists by using @see getImageInfo() method
     *
     * @return bool
     */
    public function getFileExists(){
        return !empty($this->getImageInfo());
    }

    /**
     * Gets image base name. For ex.: "image_name.ext"
     *
     * @return string
     */
    public function getImageName(){
        return basename($this->imageUrl);
    }
}