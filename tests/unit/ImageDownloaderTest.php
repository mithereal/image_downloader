<?php

use image\download\ImageValidator;
use image\download\ImageDownloader;

class ImageDownloaderTest extends PHPUnit_Framework_TestCase
{
    protected $imagesInfo = [];
    protected $formatsCodes = [];

    protected function setUp()
    {
        $this->formatsCodes = [
            'http://some_gif_image_url' => 1,
            'http://some_jpg_image_url' => 2,
            'http://some_png_image_url' => 3,
            'http://wrong_url' => false,
            'http://some_bmp_image_url' => 6
        ];

        $this->imagesInfo = [
            'http://some_gif_image_url' => [
                0 => 256,
                1 => 256,
                2 => 1,
                3 => 'width=\"256\" height=\"256\"',
                'bits' => 8,
                'channels' => 3,
                'mime' => 'image/gif',
            ],
            'http://some_jpg_image_url' => [
                0 => 1024,
                1 => 512,
                2 => 2,
                3 => 'width=\"1024\" height=\"512\"',
                'bits' => 10,
                'channels' => 5,
                'mime' => 'image/jpg',
            ],
            'http://some_png_image_url' => [
                0 => 64,
                1 => 64,
                2 => 3,
                3 => 'width=\"64\" height=\"64\"',
                'bits' => 5,
                'channels' => 3,
                'mime' => 'image/png',
            ],
            'http://wrong_url' => false,
            'http://some_bmp_image_url' => [
                0 => 512,
                1 => 256,
                2 => 6,
                3 => 'width=\"512\" height=\"256\"',
                'bits' => 16,
                'channels' => 6,
                'mime' => 'image/bmp',
            ],


        ];
    }

    /**
     * @group active
     */
    public function testGetImageFormatCode(){
        $someJpgImageUrl = 'http://some_jpg_image_url';
        $jpgImageInstance = $this->getMockBuilder('image\download\ImageInstance')
            ->setConstructorArgs([$someJpgImageUrl])
            ->setMethods(['getImageInfo'])
            ->getMock();

        $jpgImageInstance->method('getImageInfo')
            ->will($this->returnValue($this->imagesInfo[$jpgImageInstance->getImageUrl()]));

        $this->assertEquals(2, $jpgImageInstance->getImageFormatCode());

        $somePngImageUrl = 'http://some_png_image_url';
        $pngImageInstance = $this->getMockBuilder('image\download\ImageInstance')
            ->setConstructorArgs([$somePngImageUrl])
            ->setMethods(['getImageInfo'])
            ->getMock();

        $pngImageInstance->method('getImageInfo')
            ->will($this->returnValue($this->imagesInfo[$pngImageInstance->getImageUrl()]));

        $this->assertEquals('3', $pngImageInstance->getImageFormatCode());

        $emptyUrl = '';
        $emptyUrlImageInstance = $this->getMockBuilder('image\download\ImageInstance')
            ->setConstructorArgs([$emptyUrl])
            ->getMock();


        $this->assertEquals(false, $emptyUrlImageInstance->getImageFormatCode());

        $wrongUrl = 'http://wrong_url';
        $wrongUrlImageInstance = $this->getMockBuilder('image\download\ImageInstance')
            ->setConstructorArgs([$wrongUrl])
            ->setMethods(['getImageInfo'])
            ->getMock();

        $wrongUrlImageInstance ->method('getImageInfo')
            ->will($this->returnValue($this->imagesInfo[$wrongUrlImageInstance ->getImageUrl()]));

        $this->assertEquals(false, $wrongUrlImageInstance->getImageFormatCode());
    }

    /**
     * @group active
     */
    public function testImageExists(){
        $existsImageUrl = 'http://some_gif_image_url';
        $existsImageInstance = $this->getMockBuilder('image\download\ImageInstance')
            ->setConstructorArgs([$existsImageUrl])
            ->setMethods(['getImageInfo'])
            ->getMock();

        $existsImageInstance->method('getImageInfo')
            ->will($this->returnValue($this->imagesInfo[$existsImageInstance->getImageUrl()]));

        $this->assertTrue($existsImageInstance->getFileExists());

        $notExistsUrl = 'http://wrong_url';
        $notExistsImageInstance = $this->getMockBuilder('image\download\ImageInstance')
            ->setConstructorArgs([$notExistsUrl])
            ->setMethods(['getImageInfo'])
            ->getMock();

        $notExistsImageInstance->method('getImageInfo')
            ->will($this->returnValue($this->imagesInfo[$notExistsImageInstance->getImageUrl()]));

        $this->assertFalse($notExistsImageInstance->getFileExists());
    }

    /**
     * @group active
     */
    public function testValidateImage(){
        $someJpgImageUrl = 'http://some_jpg_image_url';
        $jpgImageInstance = $this->getMockBuilder('image\download\ImageInstance')
            ->setConstructorArgs([$someJpgImageUrl])
            ->setMethods(['getImageInfo'])
            ->getMock();

        $jpgImageInstance->method('getImageInfo')
            ->will($this->returnValue($this->imagesInfo[$jpgImageInstance->getImageUrl()]));

        $imageValidator =new ImageValidator(['jpg', 'png']);

        $this->assertTrue($imageValidator->validate($jpgImageInstance));

        $somePngImageUrl = 'http://some_png_image_url';
        $pngImageInstance = $this->getMockBuilder('image\download\ImageInstance')
            ->setConstructorArgs([$somePngImageUrl])
            ->setMethods(['getImageInfo'])
            ->getMock();

        $pngImageInstance->method('getImageInfo')
            ->will($this->returnValue($this->imagesInfo[$pngImageInstance->getImageUrl()]));

        $imageValidator = new ImageValidator(['jpg', 'gif']);

        $this->assertFalse($imageValidator->validate($pngImageInstance));
    }

    /**
     * @group active
     */
    public function testSaveImageWithEmptyDir(){
        $this->setExpectedException(
            'Exception', 'Wrong directory!'
        );

        $someJpgImageUrl = 'http://some_jpg_image_url';
        $jpgImageInstance = $this->getMockBuilder('image\download\ImageInstance')
            ->setConstructorArgs([$someJpgImageUrl])
            ->setMethods(['getImageInfo'])
            ->getMock();

        $jpgImageInstance->method('getImageInfo')
            ->will($this->returnValue($this->imagesInfo[$jpgImageInstance->getImageUrl()]));

        $emptyDir = '';
        $imageSaver = $this->getMockBuilder('image\download\ImageSaver')
            ->setConstructorArgs([$emptyDir])
            ->setMethods(null)
            ->getMock();

        $imageSaver->save($jpgImageInstance);
    }

    /**
     * @group active
     */
    public function testSaveImageBadDir(){
        $this->setExpectedException(
            'Exception', 'Wrong directory!'
        );

        $someJpgImageUrl = 'http://some_jpg_image_url';
        $jpgImageInstance = $this->getMockBuilder('image\download\ImageInstance')
            ->setConstructorArgs([$someJpgImageUrl])
            ->setMethods(['getImageInfo'])
            ->getMock();

        $jpgImageInstance->method('getImageInfo')
            ->will($this->returnValue($this->imagesInfo[$jpgImageInstance->getImageUrl()]));

        $dir = 'images';
        $imageSaver = $this->getMockBuilder('image\download\ImageSaver')
            ->setConstructorArgs([$dir])
            ->setMethods(['resolveDir'])
            ->getMock();

        $imageSaver->method('resolveDir')
            ->will($this->returnValue(false));

        $imageSaver->save($jpgImageInstance);
    }

    /**
     * @group active
     */
    public function testSaveImage(){
        $someJpgImageUrl = 'http://some_jpg_image_url';
        $jpgImageInstance = $this->getMockBuilder('image\download\ImageInstance')
            ->setConstructorArgs([$someJpgImageUrl])
            ->setMethods(['getImageInfo', 'getImageName'])
            ->getMock();

        $jpgImageInstance->method('getImageInfo')
            ->will($this->returnValue($this->imagesInfo[$jpgImageInstance->getImageUrl()]));
        $jpgImageInstance->method('name')
            ->will($this->returnValue('some_name'));

        $dir = 'images';
        $imageSaver = $this->getMockBuilder('image\download\ImageSaver')
            ->setConstructorArgs([$dir])
            ->setMethods(['resolveDir', 'createFile'])
            ->getMock();

        $imageSaver->method('resolveDir')
            ->will($this->returnValue(true));
        $imageSaver->method('createFile')
            ->will($this->returnValue(false));

        $this->assertFalse($imageSaver->save($jpgImageInstance));

        $imageSaver = $this->getMockBuilder('image\download\ImageSaver')
            ->setConstructorArgs([$dir])
            ->setMethods(['resolveDir', 'createFile'])
            ->getMock();

        $imageSaver->method('resolveDir')
            ->will($this->returnValue(true));
        $imageSaver->method('createFile')
            ->will($this->returnValue(true));

        $this->assertTrue($imageSaver->save($jpgImageInstance));
    }

    /**
     * @group active
     */
    public function testDownloadExistingImage()
    {
        $existingImageUrl = 'http://some_jpg_image_url';
        $savingDir = 'images'.DIRECTORY_SEPARATOR.'remote';

        $imageDownloader = $this->getMockBuilder('image\download\ImageDownloader')
            ->setMethods(['createImageInstance', 'createImageSaver'])
            ->getMock();

        $imageDownloader->method('createImageInstance')
            ->will($this->returnCallback(function(){
                $args = func_get_args();

                $existingImageInstance = $this->getMockBuilder('image\download\ImageInstance')
                    ->setConstructorArgs([$args[0]])
                    ->setMethods(['getImageInfo', 'getImageName'])
                    ->getMock();

                $existingImageInstance->method('getImageInfo')
                    ->will($this->returnValue($this->imagesInfo[$existingImageInstance->getImageUrl()]));
                $existingImageInstance->method('name')
                    ->will($this->returnValue('some_name'));

                return $existingImageInstance;
        }));

        $imageDownloader->method('createImageSaver')
            ->will($this->returnCallback(function(){
                $args = func_get_args();

                $imageSaver = $this->getMockBuilder('image\download\ImageSaver')
                    ->setConstructorArgs([$args[0]])
                    ->setMethods(['resolveDir', 'createFile'])
                    ->getMock();

                $imageSaver->method('resolveDir')
                    ->will($this->returnValue(true));
                $imageSaver->method('createFile')
                    ->will($this->returnValue(210809));

                return $imageSaver;
        }));

        $this->assertEquals(210809, $imageDownloader->download($existingImageUrl, $savingDir));
    }

    /**
     * @group active
     */
    public function testDownloadNotExistingImage()
    {
        $this->setExpectedException(
            'Exception', 'File not exists!'
        );

        $notExistedImageUrl = 'http://wrong_url';
        $savingDir = 'images'.DIRECTORY_SEPARATOR.'remote';

        $imageDownloader = $this->getMockBuilder('image\download\ImageDownloader')
            ->setMethods(['createImageInstance'/*, 'createImageSaver'*/])
            ->getMock();

        $imageDownloader->method('createImageInstance')
            ->will($this->returnCallback(function(){
                $args = func_get_args();

                $existingImageInstance = $this->getMockBuilder('image\download\ImageInstance')
                    ->setConstructorArgs([$args[0]])
                    ->setMethods(['getImageInfo', 'getImageName'])
                    ->getMock();

                $existingImageInstance->method('getImageInfo')
                    ->will($this->returnValue($this->imagesInfo[$existingImageInstance->getImageUrl()]));
                $existingImageInstance->method('name')
                    ->will($this->returnValue('wrong_name'));

                return $existingImageInstance;
        }));

        $imageDownloader->download($notExistedImageUrl, $savingDir);
    }

    /**
     * @group active
     */
    public function testDownloadNotAllowedFormatImage()
    {
        $this->setExpectedException(
            'Exception', 'Not allowed file format!'
        );

        $someBmpImageUrl = 'http://some_bmp_image_url';
        $savingDir = 'images'.DIRECTORY_SEPARATOR.'remote';

        $imageDownloader = $this->getMockBuilder('image\download\ImageDownloader')
            ->setMethods(['createImageInstance'])
            ->getMock();

        $imageDownloader->method('createImageInstance')
            ->will($this->returnCallback(function(){
                $args = func_get_args();

                $existingImageInstance = $this->getMockBuilder('image\download\ImageInstance')
                    ->setConstructorArgs([$args[0]])
                    ->setMethods(['getImageInfo', 'getImageName'])
                    ->getMock();

                $existingImageInstance->method('getImageInfo')
                    ->will($this->returnValue($this->imagesInfo[$existingImageInstance->getImageUrl()]));
                $existingImageInstance->method('name')
                    ->will($this->returnValue('wrong_name'));

                return $existingImageInstance;
        }));

        $imageDownloader->download($someBmpImageUrl, $savingDir);
    }
}