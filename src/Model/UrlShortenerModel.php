<?php
/**
 * Class UrlShortenerModel
 *
 * @filesource   UrlShortenerModel.php
 * @created      June 2023
 * @package      roddy\url-shortener
 * @author       roddy <alfrednti5000@gmail.com>
 * @copyright    2023 Alfred Nti
 * @license      MIT
 */
    namespace Roddy\UrlShortener\Model;

    use Roddy\UrlShortener\Model\UrlShortener;
    use Roddy\UrlShortener\Model\UrlShortenerModelInterface;

    class UrlShortenerModel implements UrlShortenerModelInterface{

        private static function getDataWhere(string $column, string|int $value)
        {
            return UrlShortener::where($column, $value);
        }

        /*----------------------------------------------------------------
        | Finding data
        ------------------------------------------------------------------*/

        public static function findWhere(string $column, null|string $operator=null, string|int $value)
        {
            if($operator !== null){
                return UrlShortener::where($column, $operator, $value);
            }else{
                return UrlShortener::where($column, $value);
            }
        }

        public static function findById(int $id)
        {
            return self::getDataWhere('id', $id);
        }

        public static function findByKey(string $urlKey)
        {
            return self::getDataWhere('urlKey', $urlKey);
        }

        public static function findByOriginalUrl(string $originalUrl)
        {
            return self::getDataWhere('originalUrl', $originalUrl);
        }

        public static function getAll()
        {
            return UrlShortener::all();
        }

        /*----------------------------------------------------------------
        | Deleting data
        ------------------------------------------------------------------*/

        private static function deleteDataWhere(string $column, string|int $value)
        {
            return UrlShortener::where($column, $value)->delete();
        }

        public static function deleteWhere(string $column, null|string $operator=null, string|int $value)
        {
            if($operator !== null){
                $deletedData =  UrlShortener::where($column, $operator, $value)->delete();
            }else{
                $deletedData =  UrlShortener::where($column, $value)->delete();
            }

            if(!$deletedData){
                return [
                    'status' => false,
                    'message' => 'Failed'
                ];
            }else{
                return [
                    'status' => true,
                    'message' => 'deleted successfully'
                ];
            }
        }

        public static function deleteById(int $id)
        {
            $deletedData = self::deleteDataWhere('id', $id);
            if(!$deletedData){
                return [
                    'status' => false,
                    'message' => 'Failed'
                ];
            }else{
                return [
                    'status' => true,
                    'message' => 'deleted successfully'
                ];
            }
        }

        public static function deleteByKey(string $urlKey)
        {
            $deletedData = self::deleteDataWhere('urlKey', $urlKey);
            if(!$deletedData){
                return [
                    'status' => false,
                    'message' => 'Failed'
                ];
            }else{
                return [
                    'status' => true,
                    'message' => 'deleted successfully'
                ];
            }
        }

        public static function deleteByOriginalUrl(string $originalUrl)
        {
            $deletedData = self::deleteDataWhere('originalUrl', $originalUrl);
            if(!$deletedData){
                return [
                    'status' => false,
                    'message' => 'Failed'
                ];
            }else{
                return [
                    'status' => true,
                    'message' => 'deleted successfully'
                ];
            }
        }

        /*----------------------------------------------------------------
        | Custom data query
        ------------------------------------------------------------------*/

        public static function urlShortenerDB()
        {
            return new UrlShortener;
        }
    }
