# DriftPHP Http Kernel

[![CircleCI](https://circleci.com/gh/driftphp/http-kernel.svg?style=svg)](https://circleci.com/gh/driftphp/http-kernel)

This package provides async features to the Symfony (+4.3) Kernel. This
implementation uses [ReactPHP Promise](https://github.com/reactphp/promise) 
library and paradigm for this purposes.

Some first steps for you!

- [Go to DOCS](https://driftphp.io/#/?id=the-http-kernel)

or

- [Try a demo](https://github.com/driftphp/demo)
- [Install the skeleton](https://github.com/driftphp/skeleton)

## Running Tests locally ##

In order to run the tests locally you must use one of the following snippet
```
composer update -n --prefer-dist
rm -Rf var/*
php vendor/bin/phpunit --testsuite=base --exclude-group=with-filesystem
```
__Please note:__ `phpunit` caches the compiled container when it runs. Therefore, make sure to clear the cache from `./var/test`, on any modification that causes the container to change.

