# rekalogika/temporary-url-bundle

Symfony bundle for creating temporary URLs to your resources. You provide the
resource in a plain PHP object, and a service to turn it into a HTTP response.
The framework handles the rest.

## Synopsis

```php
use Rekalogika\TemporaryUrl\Attribute\AsTemporaryUrlServer;
use Rekalogika\TemporaryUrl\TemporaryUrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;

class MyData
{
    public string $name = 'John Doe';
}

class MyDataServer
{
    #[AsTemporaryUrlServer]
    public function respond(MyData $data): Response
    {
        return new Response('My name is ' . $data->name);
    }
}

/** @var TemporaryUrlGeneratorInterface $temporaryUrlGenerator */

$myData = new MyData;
$myData->name = 'Jane Doe';
$url = $temporaryUrlGenerator->generateUrl($myData);
```

## Documentation

[rekalogika.dev/temporary-url-bundle](https://rekalogika.dev/temporary-url-bundle)

## License

MIT

## Contributing

Issues and pull requests should be filed in the GitHub repository
[rekalogika/temporary-url-bundle](https://github.com/rekalogika/temporary-url-bundle).