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


2. Publish config & migrations ```php artisan vendor:publish --tag=zohobooks```


3. Go to [Zoho Developer Console](https://accounts.zoho.com/developerconsole) and generate **Server-based Client**. It
   will provide you *Client ID* and *Client Secret*

```
Homepage URL: https://www.yourdomain.com
Authorized Redirect URIs : https://www.yourdomain.com/auth/zoho/callback
```

6. Add Client ID and Client Secret in Zohoauth Model
```
Zohoauth::create([
'user_id => YourUserID
'client_id' => YourClinetIdObtaindFromZohoInStep3
'client_secret' => YourClinetSecretObtaindFromZohoInStep3
])
```

7. You can add ```ZOHOBOOKS_DATACENTER_BASE_API_URI``` and ```ZOHOBOOKS_DATACENTER_BASE_DOMAIN``` in your ```.env``` file if
   your Database center is other than India. Respective default value is ```'https://accounts.zoho.in/'``` and ```.in```

| **Data Center**   | **Domain** | **Base API URI**              |
|-------------------|------------|-------------------------------|
| **United States** | .com       | https://accounts.zoho.com/    |
| **Europe**        | .eu        | https://accounts.zoho.eu/     |
| **India**         | .in        | https://accounts.zoho.in/     |
| **Australia**     | .com.au    | https://accounts.zoho.com.au/ |


10. Hit ```www.yourdomain.com/auth/zoho/{user_id}/first-token``` It will open Zoho Login Page. After login application will
    remember zoho token.

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
