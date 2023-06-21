<?php
/**
 * Class UrlShortenerProvider
 *
 * @filesource   UrlShortenerProvider.php
 * @created      June 2023
 * @package      roddy\url-shortener
 * @author       roddy <alfrednti5000@gmail.com>
 * @copyright    2023 Alfred Nti
 * @license      MIT
 */
namespace Roddy\UrlShortener;

use Illuminate\Support\ServiceProvider;

class UrlShortenerProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/urlshortener.php' =>  config_path('urlshortener.php'),
         ]);

         $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations'),
        ]);
    }
}
