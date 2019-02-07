<?php

use RedBeanPHP\R;

$limit = 50;

$clientType = [
    'pouet'
];

R::setup('sqlite:bdd/clients.db');

$getOffset = function ($pageNumber, $limit) {
    return (($pageNumber - 1) * $limit);
};

$queryType = [
    'findAllClients' => function ($root, $args) use ($limit, $getOffset) {
        $pageNumber = $args['page'];

        $offset = $getOffset($pageNumber, $limit);

        $clients = R::find( 'client', 'LIMIT ?, ?', [ $offset, $limit ]);

        $howManyClients = R::count( 'client');
        $pageNumberMax = ceil($howManyClients / $limit);

        $pagination = (object) array('guid' => $pageNumberMax);
        array_unshift($clients, $pagination);
        return $clients;
    },
    'findSomeClients' => function ($root, $args) use ($limit, $getOffset) {
        $searchTerm = $args['search'];
        $fieldNamed = $args['field'];
        $pageNumber = $args['page'];

        $offset = $getOffset($pageNumber, $limit);

        $clients = R::find( 'client', $fieldNamed . ' LIKE ? LIMIT ?, ?', [ '%'.$searchTerm.'%', $offset, $limit ] );

        $howManyClients = R::count( 'client', $fieldNamed . ' LIKE ? ', [ '%'.$searchTerm.'%' ] );
        $pageNumberMax = ceil($howManyClients / $limit);

        $pagination = (object) array('guid' => $pageNumberMax);
        array_unshift($clients, $pagination);
        return $clients;
    },
];

$mutationType = [
    'update' => function ($root, $args) {
        $guid = $args['guid'];
        $field = $args['field'];
        $value = $args['value'];

        $client = R::load( 'client', $guid );
        $client->$field = $value;
        $guid = R::store( $client );

        return $client;
    },
    'delete' => function ($root, $args) {
        $guid = $args['guid'];

        $client = R::load( 'client', $guid );
        R::trash( $client );

        return null;
    },
];

return [
    'Client'   => $clientType,
    'Query'    => $queryType,
    'Mutation' => $mutationType,
];