<?php

namespace image\download;

/**
 * Class for validating image instance. @see ImageInstance
 *
 * Class ImageValidator
 * @package image\download
 */
class ImageValidator{
    /**
     * Contains allowed image formats
     *
     * @var array
     */
    private $allowedFormats = [];

    /**
     * Sets accordance between image format name and format code constant. @link http://php.net/manual/en/function.exif-imagetype.php
     *
     * @var array
     */
    private $formatsCodesMap = [
        'jpg' => '2',
        'png' => '3',
        'gif' => '1',

    ];

    /**
     * Creates new ImageValidator and sets allowed image formats. For ex.: new ImageValidator(['jpg', 'png'])
     *
     * @param array $allowedFormats
     */
    function __construct($allowedFormats = []){
        $this->setAllowedFormats($allowedFormats);
    }

    /**
     * Gets image allowed formats
     *
     * @return array
     */
    public function getAllowedFormats()
    {
        return $this->allowedFormats;
    }

    /**
     * Sets image allowed formats
     *
     * @param array $allowedFormats
     */
    public function setAllowedFormats($allowedFormats = [])
    {
        $this->allowedFormats = $allowedFormats;
    }

    /**
     * Gets image format according to the $formatCode @see formatsCodesMap
     *
     * @param $formatCode
     * @return bool|mixed
     */
    public function getImageFormat($formatCode){
        $format = array_search($formatCode, $this->formatsCodesMap);
        if($format !== false){
            return $format;
        }
        return false;
    }

    /**
     * Checks if image exists and matches allowed formats @see $allowedFormats
     *
     * @param ImageInstance $imageInstance
     * @return bool
     */
    public function validate(ImageInstance $imageInstance){
        $imageFormat = $this->getImageFormat($imageInstance->getImageFormatCode());
        if(empty($imageFormat)){
            return false;
        }

        if(!in_array($imageFormat, $this->allowedFormats)){
            return false;
        }

        return true;
    }
}