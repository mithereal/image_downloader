Image downloader package
===========================

This package allows to upload image to server by image url.


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require andreaslancom/image_downloader:dev-master
```

or add

```json
 "andreaslancom/image_downloader": "dev-master"
```

to the `require` section of your composer.json.


Usage & Documentation
---------------------

This package allows to upload image to server by image url.

The following example shows how to use this package:

```php
use image\download\ImageDownloader;

// download an image

 $imageDownloader = new ImageDownloader();
 $imageDownloader->download(
            'http://some_image_addr.ext',
            'project_root_path'.DIRECTORY_SEPARATOR.'writable_directory'
 );
```

Note if upload directory $dir not exists that the system tries to create this