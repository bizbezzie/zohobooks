# Zohobooks

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

**Via Composer**

``` bash
$ composer require bizbezzie/zohobooks
```

1. install via composer ```composer require bizbezzie/zohobooks```


2. Publish config file ```php artisan vendor:publish --tag=zohobooks-config```


3. Go to [Zoho Developer Console](https://accounts.zoho.com/developerconsole) and generate **Server-based Client**. It
   will provide you *Client ID* and *Client Secret*

```
Homepage URL: https://www.yourdomain.com
Authorized Redirect URIs : https://www.yourdomain.com/auth/zoho/redirect
```

4. Add values of ```ZOHO_CLIENT_ID``` and ```ZOHO_CLIENT_SECRET``` also add value of ```ZOHO_REDIRECT_URI='/auth/zoho/callback'``` to your ```.env``` file. Zoho Redirect URL for
   authentication will be added automatically in your routes.
```
.env

ZOHO_CLIENT_ID=YourZohoClientIdHere
ZOHO_CLIENT_SECRET=YourZohoClientSecretHere
ZOHO_REDIRECT_URI='/auth/zoho/callback'
```

5.Add configuration to ```config/services.php```

```
config/services.php

'zoho' => [    
  'client_id' => env('ZOHO_CLIENT_ID'),  
  'client_secret' => env('ZOHO_CLIENT_SECRET'),  
  'redirect' => env('ZOHO_REDIRECT_URI') 
],
```

6. You can add ```ZOHOBOOKS_DATACENTER_BASE_API_URI``` and ```ZOHOBOOKS_DATACENTER_BASE_DOMAIN``` in your ```.env``` file if
   your Database center is other than India. Respective default value is ```'https://accounts.zoho.in/'``` and ```.in```

| **Data Center**   | **Domain** | **Base API URI**              |
|-------------------|------------|-------------------------------|
| **United States** | .com       | https://accounts.zoho.com/    |
| **Europe**        | .eu        | https://accounts.zoho.eu/     |
| **India**         | .in        | https://accounts.zoho.in/     |
| **Australia**     | .com.au    | https://accounts.zoho.com.au/ |

7. As this application use ```laravel/socialite``` and ```socialiteproviders/zoho``` packages internally go through their documentation if needed.


8. Add ```\SocialiteProviders\Manager\ServiceProvider::class``` to your ```providers[]``` array in ```config\app.php```.
```
config\app.php

'providers' => [
    \\ Other Providers
    
    \SocialiteProviders\Manager\ServiceProvider::class,
];
```

9. Events $ Listeners
   1. Add ```SocialiteProviders\Manager\SocialiteWasCalled``` event to your ```listen[]``` array in ```app/Providers/EventServiceProvider```.
   2. Add listener ```[SocialiteProviders\Zoho\ZohoExtendSocialite::class, 'handle']``` to the ```SocialiteProviders\Manager\SocialiteWasCalled``` that you just created.
```
app/Providers/EventServiceProvider.php

protected $listen = [
    // Other Listeners
    
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            [\SocialiteProviders\Zoho\ZohoExtendSocialite::class, 'handle'],
        ],
];
```

10. Hit ```www.yourdomain.com/auth/zoho/first-token``` It will open Zoho Login Page. After login application will
    remember zoho token in cache('zoho_token').
    You can clear it via ```php artisan cache:clear``` or updating value of ```cache('zoho_token')```. This route will be disabled after
    successful attempt.


## Usage

Write here how to use

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author@email.com instead of using the issue tracker.

## Credits

- [Author Name][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/bizbezzie/zohobooks.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/bizbezzie/zohobooks.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/bizbezzie/zohobooks/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/bizbezzie/zohobooks
[link-downloads]: https://packagist.org/packages/bizbezzie/zohobooks
[link-travis]: https://travis-ci.org/bizbezzie/zohobooks
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/bizbezzie
[link-contributors]: ../../contributors
