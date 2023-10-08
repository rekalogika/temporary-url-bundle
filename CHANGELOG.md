# Changelog

## 1.3.0

* Add a Stimulus controller to disable expired links.
* Add `temporary_url_autoexpire` Twig function.
* Add expiration in the query string of the resulting URL.

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