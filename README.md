# PlaceholderBundle

[![Travis](https://img.shields.io/travis/BernhardWebstudio/PlaceholderBundle.svg?style=flat-square)](https://api.travis-ci.org/BernhardWebstudio/PlaceholderBundle)
[![Coverage Status](https://img.shields.io/coveralls/github/BernhardWebstudio/PlaceholderBundle.svg?style=flat-square)](https://coveralls.io/github/BernhardWebstudio/PlaceholderBundle?branch=master)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?style=flat-square)](https://opensource.org/licenses/MIT)

What is this bundle?
============

Use this bundle if you are looking for an easy way to generate beautiful placeholders or thumbnails for your project. 

Do you provide a page full of images? Do you use a lazy-loader with a grey-only image? Then this bundle is 
something for you!

Depending on your configuration and your local environment, you can let the bundle return placeholders generated with 
[Primitive](https://github.com/fogleman/primitive) or [sqip](https://github.com/technopagan/sqip/blob/master/README.md).

Installation
============

You need to have the service installed which you want to use. Refer to their webpages linked above to get the installation guide.
To install this bundle, refer to the following guide.

Applications that use Symfony Flex
----------------------------------

*(This installation type is not yet supported. Please refer to the next installations instructions or create a recipe.)*
Open a command console, enter your project directory and execute:

```console
$ composer require bernhard-webstudio/placeholder-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require bernhard-webstudio/placeholder-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new BernhardWebstudio\PlaceholderBundle\BernhardWebstudioPlaceholderBundle(),
        );

        // ...
    }

    // ...
}
```

Configuration
============

The standard configuration looks like this:

```yaml
bewe_placeholder:
    service: 'bewe_placeholder.generator.primitive'
    bin: 'primitive'
    iterations: 10
```

You can change the service to `bewe_placeholder.generator.sqip` if you prefer to use sqip or even 
to your own service, as long as it implements `BernardWebstudio\PlaceholderBundle\Services\PlaceholderGeneratorInterface`.

Usage
============

## In a Controller or Service
To get the path to a placeholder of an image, request the `bewe_placeholder.provider` service. 
On this object, call `getPlaceholder($imagepath)` with the path to your image as argument.

To force generatation of an image, the service `bewe_placeholder.generator` can be used. Pass 
as arguments the input and the output path to the function `generate($input, $output)`. 
Be aware that the services do not have to output a file exactly to your output path. 
Instead, they usually append `.svg`. This is dependent on the service: call `getOutputExtension` 
to get the extension.

## In Twig
Use the `placeholder` Twig filter. Apply it on the path of your image.

## Just the URL, please
If you configure to include the routing file provided by this bundle, you can generate 
a route named `bewe_placeholder` with the parameter `imagePath`. This URL will provide you 
with the placeholder, default lazyly generated. The routing file can be included in your 
routing.yml like this, for example:
```yaml
bewe_placeholder_urls:
    resource: "@BernhardWebstudioPlaceholderBundle/Resources/config/routing.yaml"
```

Contributions
============

Contributions are welcome. Just open a PR!

## TODO

- Docs: Get load paths as parameters
- Extend Twig Extension to provide more options
- Pre-generate to be requested images
- Create [Recipe](https://github.com/symfony/recipes)