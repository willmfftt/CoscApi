<?php
return array(
    'service_manager' => array(
        'factories' => array(),
    ),
    'router' => array(
        'routes' => array(
            'bfs.rpc.register' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/register',
                    'defaults' => array(
                        'controller' => 'Bfs\\V1\\Rpc\\Register\\Controller',
                        'action' => 'register',
                    ),
                ),
            ),
            'bfs.rpc.login' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        'controller' => 'Bfs\\V1\\Rpc\\Login\\Controller',
                        'action' => 'login',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            1 => 'bfs.rpc.register',
            0 => 'bfs.rpc.login',
        ),
    ),
    'zf-rest' => array(),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Bfs\\V1\\Rpc\\Register\\Controller' => 'Json',
            'Bfs\\V1\\Rpc\\Login\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'Bfs\\V1\\Rpc\\Register\\Controller' => array(
                0 => 'application/vnd.bfs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Bfs\\V1\\Rpc\\Login\\Controller' => array(
                0 => 'application/vnd.bfs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'Bfs\\V1\\Rpc\\Register\\Controller' => array(
                0 => 'application/vnd.bfs.v1+json',
                1 => 'application/json',
            ),
            'Bfs\\V1\\Rpc\\Login\\Controller' => array(
                0 => 'application/vnd.bfs.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(),
    ),
    'controllers' => array(
        'factories' => array(
            'Bfs\\V1\\Rpc\\Register\\Controller' => 'Bfs\\V1\\Rpc\\Register\\RegisterControllerFactory',
            'Bfs\\V1\\Rpc\\Login\\Controller' => 'Bfs\\V1\\Rpc\\Login\\LoginControllerFactory',
        ),
    ),
    'zf-rpc' => array(
        'Bfs\\V1\\Rpc\\Register\\Controller' => array(
            'service_name' => 'Register',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'bfs.rpc.register',
        ),
        'Bfs\\V1\\Rpc\\Login\\Controller' => array(
            'service_name' => 'Login',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'bfs.rpc.login',
        ),
    ),
);
