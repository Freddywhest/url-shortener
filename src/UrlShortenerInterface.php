<?php

/**
 * Class UrlShortenerInterface
 *
 * @filesource   UrlShortenerInterface.php
 * @created      June 2023
 * @package      roddy\url-shortener
 * @author       roddy <alfrednti5000@gmail.com>
 * @copyright    2023 Alfred Nti
 * @license      MIT
 */
    namespace Roddy\UrlShortener;
    interface UrlShortenerInterface{
        public static function store();
        public static function generateQrCodeImage();
        public static function generateQrCodeSvg();
        public static function byUserId(int|string|null $userId=null);
        public static function customKey(string $customKey);
        public static function schedule(int $numberOfDays=0);
        public static function originalUrl(?string $originalUrl=null);
        public static function generate();
    }
