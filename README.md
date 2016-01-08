#naija_emojis

[![Build Status](https://travis-ci.org/andela-womokoro/naija_emojis.svg)](https://travis-ci.org/andela-womokoro/naija_emojis)
[![Coverage Status](https://coveralls.io/repos/andela-womokoro/naija_emojis/badge.svg?branch=master&service=github)](https://coveralls.io/github/andela-womokoro/naija_emojis?branch=master)


This is a simple RESTFul API rendering emoji service to users. It was built with PHP and implements the Slim framework and JSON web token (JWT) for user authentication.

## Install

Via Composer

``` bash
$ composer require Wilson/naija-emoji
```

## Usage

- Create a new users

```
Send a POST request to https://w-naija-emoji.herokuapp.com/register
request body:
{
    'username'  : 'wil',
    'password'  : '******'
    'name'      : 'Wilson Omokoro'
}

response
{
    'Status'    : '200'
    'Message'   : 'User registration successful.'
}
```

- User login

```
Send a POST request to https://w-naija-emoji.herokuapp.com/auth/login
request body:
{
    'username'  : 'wil',
    'password'  : '******'
}

A JSON Web Token should be returned on successful login

response
{
    'Status'    : '200'
    'Message'   : 'Login successful'
    'Token'     : 'eyJ0eXAiOiJKV1QiLCJh.bGciOiJIUzI1NiJ9.eyJpYXQiOjE0NT'
}
```

- User logout

```
Send a GET request to https://w-naija-emoji.herokuapp.com/auth/logout

request header
{
    'Authorization' : 'eyJ0eXAiOiJKV1QiLCJh.bGciOiJIUzI1NiJ9.eyJpYXQiOjE0NT';
}
```

- Create an Emoji

```
Send a POST request to https://w-naija-emoji.herokuapp.com/emojis
request body:
{
    'name'  : 'Happy'
    'emoji_char'  : 😀
    'category'  : 'Facial'
    'key_words'  : ['happy, glad, delighted']
    'created_by'  : 'Wilson Omokoro'
}
request header
{
    'Authorization' : 'eyJ0eXAiOiJKV1QiLCJh.bGciOiJIUzI1NiJ9.eyJpYXQiOjE0NT';
}

response
{
    'Status'    : '201'
    'Message'   : 'Emoji creation successful.'
}
```

- Show all Emojis in the database

```
Send a GET request to https://w-naija-emoji.herokuapp.com/emojis

Response should contain all emojis in JSON format.
```

- Get a particular Emoji

```
Send a GET request containing the Emoji's ID in the emojis table to https://w-naija-emoji.herokuapp.com/emojis

E.g. to get the third emoji:

https://w-naija-emojis.herokuapp.com/emojis/3

Response should contain the emoji in JSON format.
```

- Partialy update an Emoji

```
Send a PATCH request containing the Emoji's ID in the emojis table to https://w-naija-emoji.herokuapp.com/emojis

E.g. to update just the name of the first emoji:

https://w-naija-emojis.herokuapp.com/emojis/1

request body
{
    'name'  : 'some other name',
}
request header
{
    'Authorization' : 'eyJ0eXAiOiJKV1QiLCJh.bGciOiJIUzI1NiJ9.eyJpYXQiOjE0NT';
}

response
{
    'Status'    : '200'
    'Message'   : 'Emoji successfully updated.'
}
```

- Fully update an Emoji

```
Send a PUT request containing the Emoji's position in the emojis table to https://w-naija-emoji.herokuapp.com/emojis

E.g. to fully update the first emoji:

https://w-naija-emojis.herokuapp.com/emojis/1

request body
{
    'name'  : 'Happy'
    'emoji_char'  : 😀
    'category'  : 'Facial'
    'key_words'  : 'happy, glad, delighted'
    'created_by'  : 'Wilson Omokoro'
}
request header
{
    'Authorization' : 'eyJ0eXAiOiJKV1QiLCJh.bGciOiJIUzI1NiJ9.eyJpYXQiOjE0NT';
}

response
{
    'Status'    : '200'
    'Message'   : 'Emoji successfully updated.'
}
```

- Delete a particular Emoji

```
Send a DELETE request containing the Emoji's ID in the emojis table to https://w-naija-emoji.herokuapp.com/emojis

E.g. to DELETE the third emoji:

https://w-naija-emojis.herokuapp.com/emojis/3

request header
{
    'Authorization' : 'eyJ0eXAiOiJKV1QiLCJh.bGciOiJIUzI1NiJ9.eyJpYXQiOjE0NT';
}

response
{
    'Status'    : '200'
    'Message'   : 'Emoji 3 deletion successful.'
}
```


## Testing

If the  folder containing your test classes is "tests"

```
$ phpunit tests
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email wilson.omokoro@andela.com instead of using the issue tracker.

## Credits

Naija-emoji is maintained by Wilson Omokoro.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/league/:package_name.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/thephpleague/:package_name/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/thephpleague/:package_name.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/thephpleague/:package_name.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/league/:package_name.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/league/:package_name
[link-travis]: https://travis-ci.org/thephpleague/:package_name
[link-scrutinizer]: https://scrutinizer-ci.com/g/thephpleague/:package_name/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/thephpleague/:package_name
[link-downloads]: https://packagist.org/packages/league/:package_name
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors