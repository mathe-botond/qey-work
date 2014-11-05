# QeyWork #

QeyWork is a simple PHP Framework for testable and correct OOP projects. 

To start using it you should check out the *Hello World* project.

## Summary ##

* Summary
* Concept
* Usage
    - Initializing engine
    - Adding pages and actions
    - Create a layout
    - Using the database

## Concept ##

*QeyWork* is based on testable classes. This means there is no public static function, you could call. So how do you get hold of a service? Simply define it in a constructor parameter. The communication with *QeyWork* is done trough class names. In exchange any dependency required by your classes, defined as constructor parameters, will be provided to it automatically. This is the concept of the Dependency Injection Container coined by Martin Fowler.

This can easily lead to stringly typed projects, full of strings containing class names (e.g. `"path\to\class\MyClassName"`), but there is a solution against this. PHP 5.6 introduced the ::class constant. Thus `MyClass::class` is the same as `"path\to\class\MyClassName"`. For older PHP versions QeyWork also provides this same solution, the only thing you need is to use the autoloader shipped with it, and rename any file using such constants to MyClass.prec.php.

### Initializing engine ###

To get started you need to create an engine instance: The QeyWork class has two mandatory dependencies. It needs an instance of Locations, which will tell the framework where your application is. The second parameter is the default Page class that should be rendered.

```
$engine = new QeyWork(new Locations(), HelloWorld::class);
```