[![Tracis CI](https://travis-ci.org/kajstrom/dependency-constraints.svg?branch=master)](https://travis-ci.org/kajstrom/dependency-constraints)

# DependencyConstraints

DependencyConstraints is a static code analysis tool for creating constraints for dependencies between modules in your project.
It is intended to be used within a testing library such as PHPUnit or Codeception.

The core idea of DependencyConstraints is to shorten the feedback loop for finding changes that degrade the architecture of your application.
Instead of finding out 6 months later that you or someone else on the team added undesirable coupling into your application,
you can create tests that act as a safeguard against such changes.

DependencyConstraints is inspired by JDepend and Fitness Functions introduced in the book [Building Evolutionary Architectures](https://www.thoughtworks.com/books/building-evolutionary-architectures).

## What is considered to be a dependency

DependencyConstraints assumes the following to be dependencies:
- Using a class, interface, trait or instances of a class from another module (namespace).
- Using a function from another module.
- Using a constant from another module.

Currently globally scoped classes, functions and constants are not considered to be dependencies.

Using something from a submodule is not considered to be a dependency.

For example:

Using "MyProject\ModuleA\SomeClass" in "MyProject\ModuleB" is a dependency.

Using "MyProject\ModuleA\SubModule\SomeClass" in "MyProject\ModuleA" is not a dependency.

## Potential use cases

In a layered architecture you might want to prevent other layers of your software from becoming coupled to the presentation layer.

```php
public function testBusinessLayerDoesNotDependOnPresentationLayer()
{
    $dc = new DependencyConstraints("path/to/my/src");
    $business = $dc->getModule("MyProject\\Business");
    
    $this->assertFalse(
        $business->dependsOnModule("MyProject\\Presentation"),
        $business->describeDependenciesTo("MyProject\\Presentation")
    );
}
```

Perhaps you have a modular monolith and want to ensure that a certain module will not get coupled to another module.

```php
public function testModuleADoesNotDependUponModuleB()
{
    $dc = new DependencyConstraints("path/to/my/src");
    $moduleA = $dc->getModule("MyProject\\ModuleA");
    
    $this->assertFalse(
        $moduleA->dependsOnModule("MyProject\\ModuleB"),
        $moduleA->describeDependenciesTo("MyProject\\ModuleB")
    );
}
```

Or maybe you want to keep certain external libraries out of the Application layer in a Hexagonal Architecture.
```php
public function testApplicationLayerDoesNotDependOnSymfonyHttpFoundation()
{
    $dc = new DependencyConstraints("path/to/my/src");
    $application = $dc->getModule("MyProject\Application");
    
    $this->assertFalse(
        $application->dependsOnModule("Symfony\\HttpFoundation"),
        $application->describeDependenciesTo("Symfony\\HttpFoundation")
    );
}
```

You can also check for dependencies on a certain class. Maybe you are refactoring it out but it keeps popping up in new places all the time!

```php
public function testModuleDoesNotDependOnSingleton()
{
    $dc = new DependencyConstraints("path/to/my/src");
    $module = $dc->getModule("MyProject\\Module");
    
    $this->assertFalse($module->hasDependencyOn("MyProject\\Utils\\SingletonThatSeemedAGoodIdeaBackThen");
}
``` 

## Limitations

As a static code analysis tool DependencyConstraints can't catch absolutely everything. Let's say someone wants to be clever and do something like this:

```php
$className = "MyProject\\Module\\Class";
$instance = new $className;
```

This would not be found as a dependency when analyzing the source.

## Current status

DependencyConstraints is currently in alpha. Expect bugs, interface changes and all sorts of randomness. Feedback is appreciated.

## Why not use Pdepend?

Pdepend offers very useful metrics on the quality of the codebase, but it does not allow testing dependencies between modules.