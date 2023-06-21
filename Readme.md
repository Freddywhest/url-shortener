## Table of Contents

- [Overview](#overview)
- [Installation](#installation)
    - [Requirements](#requirements)
    - [Install the Package](#install-the-package)
    - [Publish the Config and Migrations](#publish-the-config-and-migrations)
    - [Migrate the Database](#migrate-the-database)
- [Usage](#usage)
    - [Generating Shortened URLs](#generating-shortened-urls)
        - [Quick Start](#start)
        - [Domain for the Shortened URL](#Domain-for-the-Shortened-URL)
        - [Custom Keys](#custom-keys)
        - [Set User id](#user-id)
        - [Schedule when the url can be access](#Schedule-when-the-url-can-be-access)
        - [Generate a QrCode Image for Short Url](#Generate-a-QrCode-Image-for-Short-Url)
        - [Generate a QrCode Svg for Short Url](#Generate-a-QrCode-Svg-for-Short-Url)
        - [Rules for Generating Shortened URLs](#Rules-for-Generating-Shortened-URLs)
    - [Retrieving Url](#Retrieving-Url)
        - [Find by Id](#find-by-id)
        - [Find by URL Key](#find-by-url-key)
        - [Find by Original URL](#find-by-original-url)
        - [Find Where](#find-where)
        - [Get all URLs](#get-all-urls)
    - [Deleteing Url](#Deleteing-Url)
        - [Delete by id](#delete-by-id)
        - [Delete by URL Key](#delete-by-url-key)
        - [Delete by Original URL](#delete-by-original-url)
        - [Delete Where](#delete-where-url)
    - [Get Instance of Model](#model-factories)
        - [urlShortenerDB](#urlShortenerDB)
- [Security](#security)
- [Credits](#credits)
- [License](#license)
    

## Overview

A Laravel package that can be used for adding shortened URLs to your existing web app.

## Installation
Quick note: You must have a basic understanding of the use of the artisan command and composer for installing laravel package.

### Requirements
The package has been developed and tested to work with the following minimum requirements:

- PHP >= 8.0
- Laravel >= 8.0


### Install the Package
You can install the package via Composer:
```bash
composer require roddy/url-shortener
```

### Publish the Config and Migrations
You can then publish the package's config file and database migrations by using the following command:
```bash
php artisan vendor:publish --provider="Roddy\UrlShortener\UrlShortenerProvider"
```

### Migrate the Database
This package contains a migration that add a new table to the database: ``` url_shortener ```. To run this migration, simply run the following command:
```bash
php artisan migrate
```

## Usage
### Generating Shortened URLs
#### Start
 There are two(2) ways to start using url-shortener
- [Generate Shortened Url and Store it into the Database](#generate-shortened-url-and-store-it-into-the-database)
- [Generate Shortened Url Without Storing it into the Database](#generate-shortened-url-without-storing-it-into-the-database)

 ###### Generate Shortened Url and Store it into the Database
 To store a generated shortened url, you need to add the ```store``` method.

 For example:
 ```php
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')
                            ->store()
                            ->generate();
 ```
 

 ###### Generate Shortened Url Without Storing it into the Database
 To not store a generated shortened url, you do not need to add the ```store``` method.

 For example:
 ```php
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')->generate();
 ```

 #### Domain for the Shortened URL
 By default the domain for the shortened url will be the current domain of the website. 
 For example if the domain for your website is http://mywebsite.com, your shortened url will use http://mywebsite.com as the domain.
 You can change it by setting the ```domain``` in ```config/urlshortener.php``` to the domain you want.

 #### Custom Keys
 By default, the shortened URL that is generated will contain a random url key. The url key will be of the length that you define in the config files (defaults to 7 characters). Config file can be found in ```config/urlshortener.php```.
 Example: if a URL is ```https://domain/aBc1234```, the key is ```aBc1234```.

 You may wish to define a custom url key for your shortened url rather than generating a random key. You can do this by using the ```->customKey()``` method. 

 Examples:
 1. This example will store the generated shortened url with the custom key into the database;
```php 
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')
                            ->customKey("custom-url-key") //param should be a string
                            ->store()
                            ->generate();
    // Short URL: https://domain.com/custom-key
 ```

 2. This example will generated a shortened url with the custom key without storing it into the database;
```php 
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')
                            ->customKey("custom-url-key") //param should be a string
                            ->generate();
    // Short URL: https://domain.com/custom-key
 ```

 #### User id
 Urlshortener allows you to add a user id to the generated short url to indicate the user that generated it. By default it is ```null```, you can add a user id by using the ```->byUerId()``` method.

 Examples:
 1. This example will store the generated shortened url with the user id into the database;
```php 
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')
                            ->byUserId(1) //param should be a string or int
                            ->store()
                            ->generate();
 ```

 2. This example will generated a shortened url with the user id without storing it into the database;
```php 
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')
                            ->byUserId(1) //param should be a string or int
                            ->generate();
 ```

#### Schedule when the url can be access
By default, all short URLs that you generate can be access on the day you generated it. However, you may set a
date for accessing your URLs when you're generating them. You can do this by using the ```->schedule()``` method.
The ```->schedule()``` method accepts number of days as the parameter and it must be ```int```.

Exapmles:
1. This example will store the generated shortened url with the schedule date into the database;
```php 
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')
                            ->schedule(2) //This will schedule it to the next 2 days
                            ->store()
                            ->generate();
 ```

 2. This example will generated a shortened url with the schedule date without storing it into the database;
```php 
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')
                            ->schedule(2) //This will schedule it to the next 2 days
                            ->generate();
 ```

#### Generate a QrCode Image for Short Url
You can generate a QrCode image for your short url by using the ```->generateQrCodeImage()``` method.
Note: This method generate an image url for the QrCode, example of the image url `data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKUAAAClCAIAAACyS`.
Note: You can not use ```->generateQrCodeSvg()``` and ```->generateQrCodeImage()``` at the same time, you have to use only one of them.

Examples of usage:
1. This example will store the generated shortened url with the generated QrCode Image into the database;
```php 
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')
                            ->generateQrCodeImage() //add this method
                            ->store()
                            ->generate();
 ```

 2. This example will generated a shortened url with the generated QrCode Image without storing it into the database;
```php 
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')
                            ->generateQrCodeImage() //add this method
                            ->generate();
 ```

#### Generate a QrCode Svg for Short Url
You can generate a QrCode svg for your short url by using the ```->generateQrCodeSvg()``` method.
Note: You can not use ```->generateQrCodeSvg()``` and ```->generateQrCodeImage()``` at the same time, you have to use only one of them.

Examples of usage:
1. This example will store the generated shortened url with the generated QrCode Svg into the database;
```php 
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')
                            ->generateQrCodeSvg() //add this method
                            ->store()
                            ->generate();
 ```

 2. This example will generated a shortened url with the generated QrCode Svg without storing it into the database;
```php 
    use Roddy\UrlShortener\UrlShortenerGenerator;
    UrlShortenerGenerator::originalUrl('https://orginalurl.com')
                            ->generateQrCodeSvg() //add this method
                            ->generate();
 ```

 #### Rules for Generating Shortened URLs
1. You can not use ```->generateQrCodeSvg()``` and ```->generateQrCodeImage()``` at the same time, you have to use only one of them.
2. The ```->generate()``` method should always be the last method.
3. The ```->schedule()``` method accepts only int/number as the days parameter.

### Retrieving Url
#### Find by Id
To find the ShortURL model that corresponds to a given shortened URL id, you can use the ``` findById() ``` method.

For example, to find the ShortURL model of a shortened URL that has the id ``` 1 ```, you could use the following:
```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = UrlShortenerModel::findById(1);
``` 

#### Find by URL Key
To find the ShortURL model that corresponds to a given shortened URL key, you can use the ``` findByKey() ``` method.

For example, to find the ShortURL model of a shortened URL that has the key ``` aBcD234 ```, you could use the following:
```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = UrlShortenerModel::findByKey('aBcD234');
``` 

#### Find by Original URL
To find the ShortURL model that corresponds to a given shortened URL original Url, you can use the ``` findByOriginalUrl() ``` method.

For example, to find all of the ShortURL models of shortened URLs with original url of  ``` https://originalUrl.com ```, you could use the following:

```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = UrlShortenerModel::findByOriginalUrl("https://destination.com");
```

#### Find Where
To find the ShortURL model that corresponds to a given custom query or filter, you can use the ``` findWhere() ``` method.

For example, to find all shortened url ```where id is greater than 1``` you could use the following:
```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = UrlShortenerModel::findWhere("id", ">", "1");
```
**Note**: the ``` findWhere() ``` method takes 3 parameters, which are ``` column, opertor, value ```, you can set the ```oprator``` parameter to ```null``` if you don't want to provide an ```operator```. 
Example:
```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = UrlShortenerModel::findWhere("id", null, "1");
```

#### Get all URLs
To get all Shortened URL, you can use the ``` getAll() ``` method.

Example:
```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = UrlShortenerModel::getAl);
```
### Deleteing Url

**Note:** All the delete methods returns an array.

#### Delete by id
To delete the ShortURL model that corresponds to a given shortened URL id, you can use the ``` deleteById() ``` method.

For example, to delete the ShortURL model of a shortened URL that has the id ``` 1 ```, you could use the following:
```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = UrlShortenerModel::deleteById(1);
``` 

#### Delete by URL Key
To delete the ShortURL model that corresponds to a given shortened URL key, you can use the ``` deleteByKey() ``` method.

For example, to delete the ShortURL model of a shortened URL that has the key ``` aBcD234 ```, you could use the following:
```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = UrlShortenerModel::deleteByKey('aBcD234');
``` 
#### Delete by Original URL
To delete the ShortURL model that corresponds to a given shortened URL original url, you can use the ``` deleteByOriginalUrl() ``` method.

For example, to delete the ShortURL model of a shortened URL that has the original url ``` https://originalUrl.com ```, you could use the following:
```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = UrlShortenerModel::deleteByOriginalUrl('https://originalUrl.com');
``` 
#### Delete Where
To delete the ShortURL model that corresponds to a given custom query or filter, you can use the ``` deleteWhere() ``` method.

For example, to delete all shortened url ```where id is greater than 1``` you could use the following:
```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = UrlShortenerModel::deleteWhere("id", ">", "1");
```
**Note**: the ``` deleteWhere() ``` method takes 3 parameters, which are ``` column, opertor, value ```, you can set the ```oprator``` parameter to ```null``` if you don't want to provide an ```operator```. 
Example:
```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = UrlShortenerModel::deleteWhere("id", null, "1");
```
### Get Instance of Model
#### urlShortenerDB
urlShortenerDB returns the urlShortener Model which allows you to use all the ```Eloquent``` methods provided by Laravel.
To see the Eloquent methods click [here](#https://laravel.com/docs/10.x/eloquent) or visit https://laravel.com/docs/10.x/eloquent.

Example of Usage:
```php
use Roddy\UrlShortener\Model\UrlShortenerModel
$shortURL = urlShortenerDB::findOrFail(1); //You can use any of the Eloquent query building 
```

## Security

If you find any security related issues, please contact me directly at [alfrednti5000@gmail.com](mailto:alfrednti5000@gmail.com) to report it.

## Contribution

If you wish to make any changes or improvements to the package, feel free to make a pull request.

## Credits

- [Alfred Nti](https://github.com/Freddywhest)
- [Chillerlan](https://github.com/chillerlan) (QrCode Generator)
- [All Contributors](https://github.com/Freddywhest/url-shortener/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
