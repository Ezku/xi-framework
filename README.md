# Xi framework

This repository contains the remains of a proprietary PHP application framework and library built on PHP 5.2, Zend Framework 1.x and Doctrine 1.x. In large parts it is either experimental or merely obsolete, but I am confident that there are a number of tried and tested yet still applicable gems within.

I am finally releasing the code into the wild, something I should have done a long time ago, because the framework is discontinued in the form you see here. The name should be getting a new life worthy of living any time soon - I hope you'll get to see that. In the meantime, feel free to scavenge all you can see here.

# Highlights

A list of things featured that might be of interest (in no particular order):

## Framework structures

- Configuration based dependency injection engine with nested resource namespaces and resource behaviours
- Two-staged bootstrapping (cacheable, runtime)
- An extended Zend MVC structure
    - each ActionController can be associated to one Model class (Service, really) to facilitate access to the model layer, and one View class (Presenter by current jargon) to retrieve the template's requisite data from the Model
    - multi-stage action dispatching with more options for flow control within the ActionController

## Library features

- Zend_Config decorators with Filter and, specifically, Inflector support
- Zend_Validate decorators with composite validators And and Or
- Fluent Zend_Acl configuration
- A general purpose state machine for workflow control
- Event dispatching
- Date processing beyond the DateTime features of PHP 5.2
- Doctrine behaviours for object states, ratings
- Doctrine implementations of many Auth/Validation features implemented with Zend_Db in ZF