weimob api sdk for php
------------
This package design for weimob cloud api,
you can use the Oauth class to get access token and call the api by Api class

Example
----
```php
//require class
use Weimob\Oauth\Oauth;
use Weimob\Api\Api;

//a simple example
$oauth = new Oauth([
    'clientId'                => 'demoapp',    // The client ID assigned to you by the provider
    'clientSecret'            => 'demopass',   // The client password assigned to you by the provider
    'redirectUri'             => 'http://example.com/your-redirect-url/',
    'urlAuthorize'            => 'http://brentertainment.com/oauth2/lockdin/authorize',
    'urlAccessToken'          => 'http://brentertainment.com/oauth2/lockdin/token',
    'urlResourceOwnerDetails' => 'http://brentertainment.com/oauth2/lockdin/resource'
]);
$token = $oauth->auth();

$api = new Api($token['accessToken']);
try {
    $result = $api->send('wangpu/Order/FullInfoGetHighly', [
        'order_no' => '201806211425',
        'shop_id' => '123456'
    ]);
    var_dump($result);
} catch (\Exception $e) {
    echo $e->getMessage();
}
     
```
