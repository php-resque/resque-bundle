# Resque Bundle

Resque for the Symfony Framework.

Build Status: [![Build Status](https://api.travis-ci.org/php-resque/resque-bundle.png?branch=master)](https://travis-ci.org/php-resque/resque-bundle)

This bundle brings you [PHP Resque](https://github.com/php-resque/resque) and all of it's features, plus the following:

 * Job targets can be services.
 * **!** Ability to defer jobs to `kernel.terminate`, for when you're not quite ready for managing background workers.
 * Commands to easily manage your background queue.
 * **!** Optional ability to map job targets to specific queues. So you can avoid littering the application with queue names.

It however, currently adds the complication that your background workers will need to halt/reload for application changes.

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require php-resque/resque-bundle "dev-master"
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

            new Resque\Bundle\ResqueBundle\ResqueBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Configure the Bundle

```yaml
resque:
  adapter: array|predis_resque
  array:
    process_on_terminate: true
  predis_resque:
    host: 127.0.0.1
```

## Usage

You can use the PHP Resque bundle in two ways. Console commands and in code through the `resque` service.

### Commands

**!** If you've configured the bundle to use a persisting adapter, the following commands will also act as a working example.

Job enqueue

```bash
$ app/console resque:enqueue acme 'Resque\Bundle\ResqueBundle\ExampleJob'
$ app/console resque:enqueue high-priority resque.job.example ?name=Fabian
```

Queue list

```bash
$ app/console resque:queue:list
```

Queue delete

```bash
$ app/console resque:queue:delete acme
```

Worker start

```bash
$ app/console resque:worker:start high-priority,acme
```

Worker list

```bash
$ app/console resque:queue:list
```

Worker stop

```bash
$ app/console resque:queue:stop --all
```

All commands make extensive usage of `--help`, if you want further information.

### PHP

**!** 

```php
<?php

$resque = $container->get('resque');

// @todo 
```
