# Symfony Async Kernel

[![CircleCI](https://circleci.com/gh/driftphp/http-kernel.svg?style=svg)](https://circleci.com/gh/driftphp/http-kernel)

This package provides async features to the Symfony (+4.3) Kernel. This
implementation uses [ReactPHP Promise](https://github.com/reactphp/promise) 
library and paradigm for this purposes.

## Motivations

Symfony is a great framework, but for those like me who found in ReactPHP a next
vital step for some PHP applications, Symfony is not usable anymore. At least
until now.

With this new Symfony kernel, the framework will work properly as a man in the
middle in terms of asynchronous Promises. Than means that the event loop
instanced and initiated in the websocket will be able to manage promises created
in your domain, for example, in an I/O operation. This cannot happen if the
kernel has a Request/Response architecture, but only with a 
Request/Promise(Response) one.

This is what this Kernel is about. Introduces this new architecture points and
makes your project ready to start using asynchronous features in your
application.

Take a look at [these chapters](https://medium.com/@apisearch/symfony-and-reactphp-series-82082167f6fb) 
about Symfony and ReactPHP for a better understanding.

## Installation

You can install the package with composer. This is a PHP Library, so installing
this repository will not change your original project behavior.

```yml
{
  "require": {
    "drift/http-kernel": "dev-master"
  }
}
```

Once you have the package under your vendor folder, now it's time to turn you
application asynchronous-friendly by changing your kernel implementation, from
the Symfony regular HTTP Kernel class, to the new Async one.

```php
use Symfony\Component\HttpKernel\AsyncKernel;

class Kernel extends AsyncKernel
{
    use MicroKernelTrait;
```

> With this change, nothing should happen. This async kernel maintains all back
> compatibility, so should work inside any synchronous (regular) Symfony
> project.

## Controllers

Your controller will be able to return a Promise now. It is mandatory to do
that? Non-blocking operations are always optional, so if you build your domain
blocking, this is going to work as well.

```php
/**
 * Class Controller.
 */
class Controller
{
    /**
     * Return value.
     *
     * @return Response
     */
    public function getValue(): Response
    {
        return new Response('X');
    }

    /**
     * Return fulfilled promise.
     *
     * @return PromiseInterface
     */
    public function getPromise(): PromiseInterface
    {
        return new FulfilledPromise(new Response('Y'));
    }
}
```

Both controller actions are correct.

## Event Dispatcher

Going asynchronous has some intrinsic effects, and one of these effects is that
event dispatcher has to work a little bit different. If you base all your domain
on top of Promises, your event listeners must be a little bit different. The
events dispatched are exactly the same, but the listeners attached to them must
change a little bit the implementation, depending on the expected behavior.

An event listener can return a Promise. Everything inside this promise will be
executed once the Promise is executed, and everything outside the promise will
be executed at the beginning of all listeners, just before the first one is
fulfilled.

```php
/**
 * Handle get Response.
 *
 * @param ResponseEvent $event
 *
 * @return PromiseInterface
 */
public function handleGetResponsePromiseA(ResponseEvent $event)
{
    $promise = (new FulfilledPromise())
        ->then(function () use ($event) {
        
            // This line is executed eventually after the previous listener
            // promise is fulfilled
        
            $event->setResponse(new Response('A'));
        });
        
    // This line is executed before the first event listener promise is
    // fulfilled
        
    return $promise;
}
```
