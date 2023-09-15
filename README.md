Symfony bundle for creating temporary URLs to your resources. You provide the
resource in a plain PHP object, and a service to turn it into a HTTP response.
The framework handles the rest.

## Installation

Install the package using Composer:

```bash
composer require rekalogika/temporary-url-bundle
```

Add the bundle to your `config/bundles.php`. With Symfony Flex, this should be
done automatically.

```php
return [
    // ...
    Rekalogika\TemporaryUrl\RekalogikaTemporaryUrlBundle::class => ['all' => true],
];
```

Include the route in `config/routes/rekalogika_temporary_url.yaml`. With Symfony
Flex, this should be done automatically.

```yaml
rekalogika_temporary_url:
    resource: '@RekalogikaTemporaryUrlBundle/config/routes.xml'
    prefix: /_temporary
```

**Note**: You may change the prefix if you like.

## Usage

### Creating a Resource Class

Create a class that describes your resource. There is no particular requirement
for this class, except that it must be serializable.

```php
class MyData
{
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
```

**Protip**: You can reuse your existing event, message, DTO, value objects, or
other similar classes for this purpose.

### Creating a Resource Server

Then create a server class or method that transforms the resource into an HTTP
response. Use the `AsTemporaryUrlServer` attribute to mark the method as a
temporary URL server. If the attribute is attached to the class, then the method
is assumed to be `__invoke()`. The method must accept the resource as its first
argument, and return a `Response` object.

```php
use Rekalogika\TemporaryUrl\Attribute\AsTemporaryUrlServer;
use Symfony\Component\HttpFoundation\Response;

class MyDataServer
{
    #[AsTemporaryUrlServer]
    public function respond(MyData $data): Response
    {
        return new Response('My name is ' . $data->getName());
    }
}
```

### Generating a Temporary URL in PHP

To generate a temporary URL, use the `TemporaryUrlGeneratorInterface` service.

```php
use Rekalogika\TemporaryUrl\TemporaryUrlGeneratorInterface;

/** @var TemporaryUrlGeneratorInterface $temporaryUrlGenerator */

$resource = new MyData('123');
$url = $temporaryUrlGenerator->generateUrl($resource);
```

The `TemporaryUrlGeneratorInterface::generateUrl()` offers additional options:

* `$ttl` (`int` or `DateInterval`): The time-to-live of the URL. Defaults to 30
  minutes.
* `$pinSession` (`bool`): Whether to pin the URL to the session. Pinned URLs can
  only be accessed by the same user that generated them. Defaults to `false`.
* `$referenceType`  (`int`): The type of reference to be generated (one of the
  `UrlGeneratorInterface::ABSOLUTE_*` constants). Defaults to
  `UrlGeneratorInterface::ABSOLUTE_PATH`.

### Generating a Temporary URL in Twig Templates

In a Twig template, you can use the filter `temporary_url` to generate a
temporary URL.

```twig
{# my_data here is a resource object #}
<a href="{{ my_data|temporary_url }}">Click here to download my data</a>
```

The filter accepts the same options as the `generateUrl()` method above.