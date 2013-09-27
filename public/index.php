<?php

require "../app/Twig/Autoloader.php";
Twig_Autoloader::register();
require "../app/Twig/Twig.php";

try {

    //Register an autoloader
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(
        array(
            '../app/controllers/',
            '../app/models/'
        )
    )->register();

    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();
    
    //Setting up the view component
    $di->set('view', function() {

        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir('../app/views/');
        $view->registerEngines(
            array(
                '.twig' => function($view, $di) {
                    //Setting up Twig Environment Options
                    $option = array('cache' => '../cache/');
                    $twig = new \Phalcon\Mvc\View\Engine\Twig($view, $di, $options);
                    //$twig = new Twig($view, $di, $options);
                    return $twig;
                }));

        return $view;
    });

    //Handle the request
    $application = new \Phalcon\Mvc\Application();
    $application->setDI($di);
    echo $application->handle()->getContent();

} catch(\Phalcon\Exception $e) {

     echo "PhalconException: ", $e->getMessage();
     echo "<pre>";
     print_r($e);
     echo "</pre>";
}
