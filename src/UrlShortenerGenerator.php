<?php


/**
 * Class UrlShortenerGenerator
 *
 * @filesource   UrlShortenerGenerator.php
 * @created      June 2023
 * @package      roddy\url-shortener
 * @author       roddy <alfrednti5000@gmail.com>
 * @copyright    2023 Alfred Nti
 * @license      MIT
 */
    namespace Roddy\UrlShortener;
    use Carbon\Carbon;
    use chillerlan\QRCode\QRCode;
    use Roddy\UrlShortener\UrlShortenerInterface;

    use Roddy\UrlShortener\Model\UrlShortener;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Str;

    class UrlShortenerGenerator implements UrlShortenerInterface
    {
        /** @var  string */
        private static $database = 'urlShortener';

        /** @var  string|null|int */
        private static $userId = null;

        /** @var  bool */
        private static $withModel = false;

        /** @var  bool */
        private static $generateImage = false;

        /** @var  bool */
        private static $generateSvg = false;

        /** @var  bool */
        private static $isUrlKey = false;

        /** @var  bool */
        private static $schedule = false;

        /** @var  string */
        private static string $originalUrl = '';

        /** @var  string */
        private static string $urlKey = '';

        /** @var  string */
        private static string $activeOn = '';

        /** @var  bool */
        private static $byUserId = false;

        /**
         * This method informs the generate method to store the generated short
         * url into the database
         * @return self
        */
        public static function store()
        {
            self::$withModel = true;
            return new self;
        }

        /**
         * This method informs the generate method to generate a qrcode image url
         * for the generated short url
         * @return self
        */
        public static function generateQrCodeImage()
        {
            self::$generateImage = true;
            return new self;
        }

        /**
         * This method informs the generate method to generate a qrcode svg url
         * for the generated short url
         * @return self
        */

        public static function generateQrCodeSvg(): self
        {
            self::$generateSvg = true;
            return new self;
        }

        /**
         * This method informs the generate method that there's a custom key
         * so it should validate it
         * @return self
        */

        public static function customKey(string $customKey): self
        {
            self::$isUrlKey = true;
            self::$urlKey = $customKey;
            return new self;
        }

        /**
         * This method sets the original url
         * @param string|null
         * @return self
        */

        public static function originalUrl(?string $originalUrl=null)
        {
            self::$originalUrl = $originalUrl;
            return new self;
        }

        /**
         * This method set the user id if it's provided and validate it
         * @param int|string|null
         * @return self
        */

        public static function byUserId(string|int|null $userId=null)
        {
            self::$byUserId = true;
            self::$userId = $userId;
            return new self;
        }

        /**
         * This method schedule when the generated short url will be active
         * or can be accessed;
         * @param int
         * @return self
        */

        public static function schedule(int $numberOfDays=0)
        {
            self::$schedule = true;
            if($numberOfDays > 0){
                self::$activeOn = Carbon::now()->addDays($numberOfDays)->format('Y-m-d');
            }else{
                self::$activeOn = Carbon::now()->format('Y-m-d');
            }
            return new self;
        }

        /**
         * @param string $message
         * @param null|array $data
         * @param null|int $status
         * @return array
        */

        private static function response(string $message, $data, ?int $status)
        {
            if($data !== null){
                return [
                    "status" => $status,
                    "message" => $message,
                    "data" => $data
                ];

            }

            return [
                "status" => $status,
                "message" => $message
            ];
        }

        /**
         * This method generate and check the errors
         * of the short url
         * @return array
        */

        public static function generate()
        {
            if(self::$withModel && !Schema::hasTable(self::$database))
            {
                return throw new \Exception("[".self::$database."] table does not exist in your database. Refer to {docs website} for guide.", 1);
            }

            if(self::$withModel && !Schema::hasColumns(self::$database, ['id', 'originalUrl', 'urlKey', 'active', 'userId', 'activeOn', 'hasSvg', 'hasImage', 'createdAt']))
            {
                return throw new \Exception("[".self::$database."] table must contain these columns:  ['id', 'originalUrl', 'urlKey', 'active', 'userId', 'activeOn', 'hasSvg', 'hasImage', 'createdAt']. Refer to {docs website} for guide.", 1);
            }

            if(self::$originalUrl == null)
            {
                return throw new \Exception("original url should not be null or empty", 1);
            }

            if(self::$byUserId && self::$userId == null)
            {
                return throw new \Exception("User id must not be null or empty", 1);
            }

            if(self::$generateImage && self::$generateSvg)
            {
                return throw new \Exception("You can not generate image and svg at the same time", 1);
            }

            if(self::$isUrlKey && empty(self::$urlKey))
            {
                return throw new \Exception("Url Key should not be empty", 1);
            }

            if(self::$isUrlKey && !empty(self::$urlKey) && UrlShortener::where('urlKey', self::$urlKey)->exists())
            {
                return self::response(false, null, "Url Key already exists");
            }

            if(!self::$isUrlKey){
                if(!is_int((int) config('urlshortener.url_key_length')))
                {
                    return throw new \Exception("url_key_length must be a number", 1);
                }
                else if((int) config('urlshortener.url_key_length') < 4)
                {
                    return throw new \Exception("url_key_length must be 4 or greater", 1);
                }
                do {
                    self::$urlKey = Str::random((int) config('urlshortener.url_key_length'));
                } while (UrlShortener::where('urlKey', self::$urlKey)->exists());
            }

            if(config('urlshortener.domain') !== "default" && !filter_var(config('urlshortener.domain'), FILTER_VALIDATE_URL))
            {
                return throw new \Exception("Domain url [".config('urlshortener.domain')."] set in config/urlshortener.php is not a valid url", 1);

            }

            if(config('urlshortener.domain') !== "default"){
                $fullShortUrl = rtrim(config('urlshortener.domain'), '/');
            }else{
                $fullShortUrl = rtrim(stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://'.$_SERVER['HTTP_HOST'], '/');
            }

            if(self::$generateSvg)
            {
                $generatedSvg = '
                <svg version="1.1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns'.':'.'xlink="http://www.w3.org/1999/xlink">
                    <image xlink'.':'.'href="'.(new QRCode)->render($fullShortUrl.'/'.self::$urlKey).'"/>
                </svg>
                ';
            }else if(self::$generateImage){
                $generatedImage = (new QRCode)->render($fullShortUrl.'/'.self::$urlKey);
            }

            if(self::$withModel === true){
                $createdShortUrl = UrlShortener::create([
                    'originalUrl' => self::$originalUrl,
                    'urlKey' => self::$urlKey,
                    'userId' => self::$userId,
                    'activeOn' => self::$schedule ? self::$activeOn : Carbon::now()->format('Y-m-d'),
                    'hasSvg' => self::$generateSvg ? $generatedSvg : null,
                    'hasImage' => self::$generateImage ? $generatedImage : null,
                    'shortUrl' => $fullShortUrl.'/'.self::$urlKey
                ]);

            }else{
                $createdShortUrl = collect([
                    'originalUrl' => self::$originalUrl,
                    'urlKey' => self::$urlKey,
                    'userId' => self::$userId,
                    'activeOn' => self::$schedule ? self::$activeOn : Carbon::now()->format('Y-m-d'),
                    'hasSvg' => self::$generateSvg ? $generatedSvg : null,
                    'hasImage' => self::$generateImage ? $generatedImage : null,
                    'shortUrl' => $fullShortUrl.'/'.self::$urlKey
                ]);

            }


            return self::response("success", $createdShortUrl, true);

        }
    }
