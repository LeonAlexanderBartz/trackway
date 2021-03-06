Master: [![Build Status](https://travis-ci.org/trackway-project/trackway.svg?branch=master)](https://travis-ci.org/trackway-project/trackway) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/trackway-project/trackway/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/trackway-project/trackway/?branch=master)

Trackway
========================
The simple on-premise open source time tracker.

Enjoy!

## Requirements
* PHP 5.6+
* Composer
* npm

## Installation
* `composer install`
* `npm install`
* `php bin/console doctrine:database:create`
* `php bin/console doctrine:schema:create`
* `php bin/console doctrine:fixtures:load`
* `node_modules/.bin/bower install`
* `node_modules/.bin/gulp`
* `node_modules/.bin/gulp favicons`

## Reset Database
* `php bin/console doctrine:database:drop --force`
* `php bin/console doctrine:database:create`
* `php bin/console doctrine:schema:create`
* `php bin/console doctrine:fixtures:load -n`

## Development
* Changed composer.json? Run `composer update`
* Changed package.json? Run `npm update`
* Changed bower.json? Run `bower update`
* Changed src/AppBundle/Resources/private/favicon.png? Run `gulp favicons`
* Changed anything else in src/AppBundle/Resources/private? Run `gulp`
* Changed anything in src/AppBundle/Entity? Run `php bin/console doctrine:schema:update`

## Thanks
* http://bower.io/
* http://getbootstrap.com/
* http://getcomposer.org/
* http://github.com/almasaeed2010/AdminLTE
* http://gulpjs.com/
* http://npmjs.com
* http://symfony.com/

## Author
* Mewes Kochheim

## Contributors
* Markus Wanjura
* Felix Peters
* Dominik Schmeiser

## Copyright and License
Code released under the MIT License.
