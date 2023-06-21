<?php
/**
 * Class UrlShortenerModelInterface
 *
 * @filesource   UrlShortenerModelInterface.php
 * @created      June 2023
 * @package      roddy\url-shortener
 * @author       roddy <alfrednti5000@gmail.com>
 * @copyright    2023 Alfred Nti
 * @license      MIT
 */
    namespace Roddy\UrlShortener\Model;
    interface UrlShortenerModelInterface{
        public static function findByKey(string $urlKey);
        public static function findById(int $id);
        public static function findByOriginalUrl(string $originalUrl);
        public static function findWhere(string $column, null|string $operator=null, string|int $value);
        public static function getAll();
        public static function deleteWhere(string $column, null|string $operator=null, string|int $value);
        public static function deleteById(int $id);
        public static function deleteByKey(string $urlKey);
        public static function deleteByOriginalUrl(string $originalUrl);
        public static function urlShortenerDB();
    }
