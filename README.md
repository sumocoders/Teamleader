# Teamleader class

## Oauth2
This package uses an old api version of Teamleader. To use the new version with oauth2, have a look at [sumocoders/teamleader-oauth2](https://github.com/sumocoders/teamleader-oauth2)

## Installation

`composer require sumocoders/teamleader`

## Usage

```php
$teamleader = new Teamleader('myApiGroup', 'myApiSecret');

// do one of the calls on the teamleader object, see inline docs for more info
$teamleader->getDepartments();
```

## About

PHP Teamleader is a (wrapper)class to communicate with your [Teamleader](http://www.teamleader.be)-instance.

## Documentation

The class is well documented inline. If you use a decent IDE you'll see that each method is documented with PHPDoc.
