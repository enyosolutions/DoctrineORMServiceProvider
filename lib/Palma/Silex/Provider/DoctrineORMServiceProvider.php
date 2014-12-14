<?php
/**
 * MIT License
 * ===========
 *
 * Copyright (c) 2012 Francisco Javier Palma <palmasev@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author      Francisco Javier Palma <palmasev@gmail.com>
 * @copyright   2012 Francisco Javier Palma.
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link        http://fjavierpalma.es
 */
namespace Palma\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\Common\EventManager;
use Doctrine\Common\Cache\ArrayCache;

class DoctrineORMServiceProvider implements ServiceProviderInterface {
    public function register(Application $app){
        $app['orm.configuration'] = $app->share(function($app){
            $configuration = new Configuration();
            if(isset($app['orm.metadata_cache'])){
	            $configuration->setMetadataCacheImpl($app['orm.metadata_cache']);
	        } else {
	        	$configuration->setMetadataCacheImpl(new ArrayCache());
	        }
	        
	    if(isset($app['orm.entities_namespace'])){
                AnnotationRegistry::registerAutoloadNamespace($app['orm.entities_namespace'], $app['orm.entities_path']);
            }
            
            $driverImpl = $configuration->newDefaultAnnotationDriver($app['orm.entities_path'],false);
            $configuration->setMetadataDriverImpl($driverImpl);

            if(isset($app['orm.query_cache'])){
	            $configuration->setQueryCacheImpl($app['orm.query_cache']);
	        } else {
	        	$configuration->setQueryCacheImpl(new ArrayCache());
            }

            if(isset($app['orm.result_cache'])){
                $configuration->setResultCacheImpl($app['orm.result_cache']);
            }

            $configuration->setProxyDir($app['orm.proxies_path']);
            $configuration->setProxyNamespace($app['orm.proxies_namespace']);
            $configuration->setAutogenerateProxyClasses(false);
            if(isset($app['orm.autogenerate_proxy_classes'])){
                $configuration->setAutogenerateProxyClasses($app['orm.autogenerate_proxy_classes']);
			} else {
				$configuration->setAutogenerateProxyClasses(true);
			}
            return $configuration;
        });

        $app['orm.connection'] = $app->share(function($app){
            return DriverManager::getConnection($app['orm.connection_parameters'], $app['orm.configuration'], new EventManager());
        });

        $app['orm.em'] = $app->share(function($app) {
            return EntityManager::create($app['orm.connection'], $app['orm.configuration'], $app['orm.connection']->getEventManager());
        });
		
    }

    public function boot(Application $app) {
        
    }
}

?>
