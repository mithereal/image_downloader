<?php
/**
 * Main class for downloading image by url
 */

namespace image\download;

/**
 * Class ImageDownloader. Main class for downloading image by url
 * @package image\download
 */

class ImageDownloader{

    /**
     * Creates image instance by image url
     *
     * @param $imageUrl
     * @return ImageInstance
     */
    protected function createImageInstance($imageUrl){
        return new ImageInstance($imageUrl);
    }

    /**
     * Creates image saver with setting directory $dir for saving image
     *
     * @param $dir
     * @return ImageSaver
     */
    protected function createImageSaver($dir){
        return new ImageSaver($dir);
    }

    /**
     * Main method for downloading image by $imageUrl and save it to the directory $dir
     *
     * @param $imageUrl
     * @param $dir
     * @return int - file size in bytes
     * @throws \Exception
     */
    public function download($imageUrl, $dir){
        $imageInstance = $this->createImageInstance($imageUrl);
        if(!$imageInstance->getFileExists()){
            throw new \Exception('File not exists!');
        }

        $imageValidator = new ImageValidator(['jpg', 'png', 'gif']);
        if(!$imageValidator->validate($imageInstance)){
            throw new \Exception('Not allowed file format!');
        }

        $imageSaver = $this->createImageSaver($dir);
        return $imageSaver->save($imageInstance);
    }
}