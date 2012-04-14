<?php
error_reporting(E_ALL & ~E_STRICT);
ini_set("display_errors", 1);

use Nette\Application\Routers\Route,
	Nette\Diagnostics\Debugger;

require __DIR__ . '/../libs/Nette/nette.min.php';

Debugger::$strictMode = TRUE;
Debugger::$logDirectory = __DIR__ . '/../log';

$configurator = new Nette\Configurator;

$configurator->container->params += $params;
$configurator->container->params['tempDir'] = __DIR__ . '/../tmp';
$container = $configurator->loadConfig(__DIR__ . '/config.neon');

$container->params['productionMode'] = false;

Debugger::enable($container->params['productionMode']);

$container->robotLoader->register();

$container->addService('dibi', function(Nette\DI\Container $container) {
		$dibiConnection = new \DibiConnection($container->params['database']);
		$dibiConnection->query('SET NAMES UTF8');
		return $dibiConnection;
    });

// this can probably be done better
$container->addService('model', function(Nette\DI\Container $container) {
		return Nette\ArrayHash::from(array(
				'joomT'  => new JoomTeeModel($container)
			));
	});

$container->router[] = new Route('<id editable>', array(
	    'presenter' => 'Base',
    	'action' => 'default',
	    'id' => 'editable',
	), Route::ONE_WAY);

$container->router[] = new Route('<id complex|editable>', array(
        'presenter' => 'Base',
        'action' => 'default',
        'id' => null,
	));
	

$container->application->run();
