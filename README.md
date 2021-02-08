# Performist

[![Build Status](https://www.travis-ci.com/mradhi/performist.svg?branch=main)](https://www.travis-ci.com/mradhi/performist)
[![Coverage Status](https://coveralls.io/repos/github/mradhi/performist/badge.svg?branch=main)](https://coveralls.io/github/mradhi/performist?branch=main)

A standalone action performer library without dependencies.

## Installation

```bash
composer require guennichi/performist
```

## Usage

### Action Performer

```php
class RegisterUser
{
    protected string $username;
    protected string $password;
    
    public function __construct(string $username, string $password) 
    {
        $this->username = $username;
        $this->password = $password;
    }
    
   public  function getUsername(): string
   {
        return $this->username;
   }
   
   public  function getPassword(): string
   {
        return $this->password;
   }
}

class RegisterUserHandler implements \Guennichi\Performist\HandlerInterface
{
    // Inject DB and other services here...
    
    public function __invoke(RegisterUser $action)
    {
        $user = new User($action->getUsername(), $action->getPassword());
        
        // Encode password
        
        $this->repository->add($user);
        
        // Send email notification etc...
        
        return $user;
    }
}

// Register actions/handlers
$registry = new \Guennichi\Performist\Registry();
$registry->add(RegisterUser::class, new RegisterUserHandler());
$registry->add(OtherAction::class, new OtherActionHandler());

// Instantiate the performer.
$performer = new \Guennichi\Performist\Performer($registry, new \Guennichi\Performist\HandlerPeeler());

// Do the job
$user = $performer->perform(new RegisterUser('foo@bar.com', 'password'), [
    new DoctrineTransactionMiddleware(),
    new LoggerMiddleware(),
    // ...
]);

// Generic job
$result = $performer->perform(new OtherAction(/** some data */), [
    new BetweenMiddleware(),
    new BeforeMiddleware(),
    new AfterMiddleware() 
]);
```

### Middlewares

To be able to use the middleware feature for this library, you need to create a class that
implements `\Performist\MiddlewareInterface`:

```php
class MyMiddleware implements \Guennichi\Performist\MiddlewareInterface
{
    public function handle($action, Closure $next)
    {
        // Do something here before performing the action...
        // ...
        
        $result = $next($action);
        
        // Do something here after performing the action...
        // ...
        
        return $result;
    }
}

$result = $performer->perform(new MyAction(/** some data */), [
    new MyMiddleware()
]);

```

## Supporting PHP >= 7.4

This client library only supports the latest version PHP >= 7.4 , Check [Supported Versions](https://www.php.net/supported-versions.php)
for more information.
