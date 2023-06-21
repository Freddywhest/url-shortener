<?php
    return [
    /*
    |--------------------------------------------------------------------------
    | Domain for short url generation
    |--------------------------------------------------------------------------
    |
    | This value is the domain for your generated short url.
    | Default means it will automatically use your current domain of website
    | when generating short url. If your website domain is http://example.com,
    | setting domain as default will use http://example.com when generating short url.
    | domain can be default or a valid url with starts with http:// or https://.
    |
    */
        "domain" => "default",


    /*
    |--------------------------------------------------------------------------
    | URL Length
    |--------------------------------------------------------------------------
    |
    | The character length of the shortened URL. The 'url_key_length' must be
    | at least 4. However, for performance reasons, it is recommended to
    | not use a 'url_key_length' lower than 7.
    |
    | e.g. - Using a length of 4 would result in yourdomain.com/XXXX
    |      - Using a length of 7 would result in yourdomain.com/XXXXXXX
    |
    */

        "url_key_length" => 7,
    ];
