# Upload Resized Image with PHP

Pick up aspect ratio when uploading images to your server. Build under [Laravel 5.2](https://laravel.com/). To view a demo, head over to [this](http://www.jmkleger.com/image) blog post. To have the process fully explained, read [this](http://wwww.jmkleger.com/upload-a-resized-image-with-php) article.

## Example

Original image and uploaded image:
![transformation](https://github.com/jeanquark/uploadResizedImage/raw/master/public/example.jpg "Transformation process")

## Installation

Make sure you have a running web server on your computer (for example xampp). Open your favorite CLI and move to the folder that will hold the project (typically C:\xampp\htdocs for xampp users). Then type the following commands: 

First clone the repo
```
git clone https://github.com/jeanquark/uploadResizedImage.git
```

Install required dependencies
```
composer update
```

Generate application key 
```
php artisan key:generate
```

Head over to the homepage and click the upload image link.


## Screenshots
Homepage:
![homepage](https://github.com/jeanquark/uploadResizedImage/raw/master/public/screenshot1.png "Homepage")

Upload page:
![upload](https://github.com/jeanquark/uploadResizedImage/raw/master/public/screenshot2.png "Upload")

Upload result:
![upload_result](https://github.com/jeanquark/uploadResizedImage/raw/master/public/screenshot3.png "Upload result")
