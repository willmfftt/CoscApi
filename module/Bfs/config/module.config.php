<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Bfs\\V1\\Rest\\Band\\BandResource' => 'Bfs\\V1\\Rest\\Band\\BandResourceFactory',
        ),
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
            'bfs.rest.band' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/band[/:band_id]',
                    'defaults' => array(
                        'controller' => 'Bfs\\V1\\Rest\\Band\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            1 => 'bfs.rpc.register',
            0 => 'bfs.rpc.login',
            2 => 'bfs.rest.band',
        ),
    ),
    'zf-rest' => array(
        'Bfs\\V1\\Rest\\Band\\Controller' => array(
            'listener' => 'Bfs\\V1\\Rest\\Band\\BandResource',
            'route_name' => 'bfs.rest.band',
            'route_identifier_name' => 'band_id',
            'collection_name' => 'band',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Bfs\\V1\\Rest\\Band\\BandEntity',
            'collection_class' => 'Bfs\\V1\\Rest\\Band\\BandCollection',
            'service_name' => 'Band',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Bfs\\V1\\Rpc\\Register\\Controller' => 'Json',
            'Bfs\\V1\\Rpc\\Login\\Controller' => 'Json',
            'Bfs\\V1\\Rest\\Band\\Controller' => 'HalJson',
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
            'Bfs\\V1\\Rest\\Band\\Controller' => array(
                0 => 'application/vnd.bfs.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
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
            'Bfs\\V1\\Rest\\Band\\Controller' => array(
                0 => 'application/vnd.bfs.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Bfs\\V1\\Rest\\Band\\BandEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'bfs.rest.band',
                'route_identifier_name' => 'band_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Bfs\\V1\\Rest\\Band\\BandCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'bfs.rest.band',
                'route_identifier_name' => 'band_id',
                'is_collection' => true,
            ),
        ),
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
