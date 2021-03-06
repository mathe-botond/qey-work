# QeyWork #

QeyWork is a lightweight PHP Framework promoting correct OOP usage and testable code. Powered by a modified [Dice](https://github.com/Level-2/Dice) Dependency Injection Container. 

## Summary ##

* Summary
* Concept
* Usage
    - Initializing engine
    - Adding pages and actions
    - Create a layout
    - Using the database

## Concept ##

### Dependency Injection ###

*QeyWork* is based on testable classes. This means there is no public static function, you could call. So how do you get hold of a service? Simply define it in a constructor parameter. The communication with *QeyWork* is done trough class names, and the class instatiation is done by the framework. In exchange any dependency required by your classes, defined as constructor parameters, will be provided to it automatically. This is the concept of the Dependency Injection Container coined by Martin Fowler.

### Pages and actions ###

A Page is a class that returns a graphical interface, a content wrapped inside a layout. An Action processes submitted data, returns a raw response or redirects to another page address. For example an AJAX would call for an Action, a menu item would contain links to Pages.

By default, the difference between a Page URL and an Action URL is a /q/ path parameter, handled by the .htaccess rewrite engine. Thus to call for a page named `hello` the URL will look something like:
```
http://mydomain.com/mysite/hello/
```

For an Action with the same name:
```
http://mydomain.com/mysite/q/hello/
```

The .htaccess will direct page requests to index.php and action requests to dispatcher.php.

### Is it an MVC framework? ###

No, QeyWork is not an MVC framework. It was created for thin clients, where in most cases the single responsibility of a controller would be to return a specific view. A Page would be a View in MVC, an Action would be a Controller. The main difference is, that they are decoupled. Actions don't know about Pages and vice versa.

## Usage ##

### Initializing engine ###

To get started you need to create an instance of the QeyWork class. This class has two mandatory dependencies. It needs an instance of Locations, which will tell the framework where your application is. The second parameter is the default Page class.

```
//website.php

$website = new QeyWork(new Locations(), HelloWorld::class);
```

`QeyWork::render()` function renders the current page:
```
//index.php
include('website.php');
echo $website->render();
```

Now we can implement a HelloWorld page that echoes 'Hello World'. QeyWork pages need to extends the `Page` baseclass:

```
//HelloWorld.php

class Home extends Page {
    public function render(HtmlBuilder $h) {
        return new TextNode('Hello world');
    }    
}
```

To run the current action (the equivalent of `QeyWork::render()` for Actions) call `QeyWork::run()`.

//dispatcher.php
```
include "website.php";
echo $website->run();
```

### Adding pages and actions ###
To add a Page or an Action to the website you can either add a Router of register the Page/Action:

Adding a new Page to the website:

//website.php
```
...
$website->registerPageClass('page-name', AnotherPageClass::class);
```

This page will be rendered if the following URL is called:
```
http://mydomain.com/mysite/page-name/
```

The same method goes to Actions as well:
//website.php
```
...
$website->registerActionClass('action-name', ActionClass::class);
```

Corresponding URL:
```
http://mydomain.com/mysite/q/action-name/
```
