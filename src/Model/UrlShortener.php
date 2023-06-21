<?php
/**
 * Class UrlShortenerGenerator
 *
 * @filesource   UrlShortener.php
 * @created      June 2023
 * @package      roddy\url-shortener
 * @author       roddy <alfrednti5000@gmail.com>
 * @copyright    2023 Alfred Nti
 * @license      MIT
 */
    namespace Roddy\UrlShortener\Model;

    use Illuminate\Database\Eloquent\Model;

    class UrlShortener extends Model
    {
        protected $table = 'urlShortener';
        const CREATED_AT = 'createdAt';
        const UPDATED_AT = null;
        protected $fillable = ['originalUrl', 'urlKey', 'userId', 'activeOn', 'hasSvg', 'hasImage', 'shortUrl', 'active'];
    }
