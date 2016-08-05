# Friday
Friday Flat Template Engine

This software is open source. It would be nice, if you credit me
when you make use or redistribute modified or redistribute 
unmodified pieces of my code.

Friday is developed and maintained by Matthias `nihylum` Kaschubowski.

### Introduction

Friday is a template engine that implements flat templating (without template inheritance).
Friday is extensible and can aggregate values from objects.

### Requirements

- PHP 7.0+

### Basic usage

File `index.php`
```php
use Nihylum\Friday\Engine;

$engine = new Engine(__DIR__);
$engine->mount('bar', __DIR__.'/aliased/bar');
$engine->mount('foo', __DIR__.'/aliased/foo');

echo $engine->render('template', ['John Doe']);
```

File `template.html`
```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Template Test Page</title>
    </head>
    <body>
        <p>Hello, <strong><?= $this->name ?></strong>!</p>
        <?= $this->include('@foo:test-2') ?>
        <?= $this->include('@bar:test-1') ?>
    </body>
</html>
```

File `aliased/foo/test-2.tpl`
```html
<div><kbd>test-2</kbd>-Template</div>
```

File `aliased/bar/test-1.tpl`
```html
<div><kbd>test-1</kbd>-Template</div>
```

Results in
```
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Template Test Page</title>
    </head>
    <body>
        <p>Hello, <strong>John Doe</strong>!</p>
        <div><kbd>test-2</kbd>-Template</div>
        <div><kbd>test-1</kbd>-Template</div>
    </body>
</html>
```

### API

Friday has 3 user land engine setters:

##### Attaching template functions

`Engine::setFunction($name, $callback)`

Each function will receive the `Engine`- and `Template`-class instances
where they are executed in as its first two arguments. All following arguments
are applied as passed when calling the function. Template Functions must not
return anything, but return usually a string.

All template functions defined in the `Nihylum\Friday\Command` namespace
are applied by default:

- `$this->raw($name)` returns the raw value of an assingment
- `$this->format($pattern, ... $params)` sprintf implementation
- `$this->date($variant, $format = 'Y-m-d H:i:s)` formats a date time representation
like a unix-timestamp, a date time string or a DateTimeInterface implementing object
formatted by the provided `$format`-string. This implementation utilizes
DateTime and all string-definitions are limited to what date_create() is able
to do.
- `$this->load($templateName)` returns a template instance for the given template name
- `$this->include($templateName, ... $variant)` renders a template, the given arguments
do decide what should be delivered to the template. If no `... $variant` argument is
passed, the included template will inherit all assignments from the template
that is utilizing this template function. You may define a string as an argument to
enqueue it as a target-key pulled from the calling template's assignments. You may
define an array that defines assignments. Arguments are processed as they come.
An *import* of `foo` will be overwritten by an array keeping an `foo`-key and vice versa.

##### Attaching aggregation

`Engine::setAggregator($className, $callback)`

Each aggregator will receive the targeted object as its first argument
and should return something *aggregated*.

##### Modifying the directory resolving process

`Engine::setDirectoryResolver($resolver)`

You may implement your own directory resolver. Just implement the
`Nihylum\Friday\DirectoryResolverInterface`.

##### Assignments

Assignments can be done globally, locally or by call. All of them
will overwrite upper assignments:

- globally assignments are *overwritten* for the template rendering process
when the local assignment container owns a key with the same name
- local assignments are *overwritten* for the template rendering process
when the by-call assignment defines a key with the same name.

Assignments can not be *overwritten* inside template sources by default.
If you need this feature, you have to implement your own template
function for this purpose. Template functions do have access to the current
template and may grab and modify the assignment container instance
utilizing `Template::getAssignments()` to achieve this.

##### Preparing Templates

Beside the `Engine::render($templateName, $assignments)`-method, there is
an alternative `Engine::load($templateName, $extension = null)`-method which
allows to create an `Nihylum\Friday\Template`-instances with an optionally
attached (not enforced) extension. Attached extensions are applied to the list
of extensions for which friday will lookup at the directories.

##### Exclusive Rendering Mechanism

Friday utilizes an exclusive output-buffering based rendering
mechanism that jails the template into the *static* scope of a
`Nihylum\Friday\Entity\Scope`-instance. The exclusive scope
avoids damage to the integrity of defined functions or assignments.

### ToDo

- Adding Composer Support
- Unit tests