# Changelog

## 1.6.1

* deps: update javascript dependencies

## 1.6.0

* feat: PHP 8.4 compatibility

## 1.5.0

* build: add dependabot config
* test: add missing test for PHP 8.3 and Symfony 7
* dep: bump minimum PHP to 8.2
* dep: bump phpunit to 10
* fix: fix deprecation warnings

## 1.4.1

* feat: Supports AssetMapper

## 1.4.0

* Supports Symfony 7

## 1.3.1

* Fix typehints

## 1.3.0

* Add a Stimulus controller to disable expired links.
* Add `temporary_url_autoexpire` Twig function.
* Add expiration in the query string of the resulting URL.
* Make the Stimulus controller to expire links using the expiration in the
  query string.

## 1.2.1

* php-cs-fixer run
## 1.2.0

* Temporary URL resource transformer to help accept non-serializable
  resources.
  
## 1.1.1

* Use regex for Twig testing
* Add tests for Union types
* Add matrix to CI
* Bump Symfony requirement to 6.3 (because of WithHttpStatus)

## 1.1.0

* Add 'temporary_url' Twig filter