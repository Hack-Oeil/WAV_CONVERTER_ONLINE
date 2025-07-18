<?php

return function(\FastRoute\RouteCollector $router) {
  
    // Page d'accueil
    $router->get('/', 'App\Controller\HomeController::print');
    $router->post('/', 'App\Controller\HomeController::print');
};
