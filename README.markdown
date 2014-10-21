DoctrineORM Service Provider for Silex
======================================
This library provides you a Silex DoctrineORM Service provider.

This service provider requires PHP 5.3+, Silex and DoctrineORM.

DoctrineORM is included in composer.json.

Parameters
----------
* **orm.entities_path**: The path to entities.
* **orm.proxies_path**: The path to proxies.
* **orm.proxies_namespace**: The proxies namespace.
* **orm.connection_parameters**: Array of Doctrine DBAL connection params. The DBAL configuration is explained in [DBAL configuration](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html)
* **orm.metadata_cache**: Optional. This param sets the cache implementation to use for caching metadata information. The default value is Doctrine\Common\Cache\ArrayCache. Don't use it for production.
* **orm.query_cache**: Optional. This param sets the cache implementation to use for caching DQL queries. The default value is Doctrine\Common\Cache\ArrayCache. Don't use it for production.
* **orm.result_cache**: Optional. This param sets the cache implementation to use for caching query results information.
* **orm.autogenerate_proxy_classes**: Sets whether proxy classes should be generated automatically at runtime by Doctrine. If set to FALSE, proxy classes must be generated manually through the doctrine command line task generate-proxies. The strongly recommended value for a production environment is FALSE. The default value is TRUE.

Registering
-----------
```php
<?php
...

$app->register(new Palma\Silex\Provider\DoctrineORMServiceProvider(), array(
    'orm.entities_path'     => __DIR__.'/Entities/',
    'orm.proxies_path'      => __DIR__.'/Proxies',
    'orm.proxies_namespace' => 'MyProject\Proxies',
    'orm.connection_parameters' => array(
    			'driver'        => 'pdo_mysql',
                'dbname'        => 'dbname',
                'user'          => 'usename',
                'password'      => '******',
                'host'          => 'localhost',
                'charset'       => 'utf8',
    		)
));
```

Usage
-----

```php

<?php

$app->get('/blog/show/{id}', function ($id) use ($app) {
    
    $post = $['orm.em']->find('Post', $id);
	
	return  "<h1>{$post->getTitle()}</h1>".
            "<p>{$post->getBody()}</p>";
});

```

[![Total Downloads](https://poser.pugx.org/palma/DoctrineORMServiceProvider/d/total.png)](https://packagist.org/packages/enyoslutions/doctrine-orm-service-provider)
