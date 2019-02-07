<?php
use GraphQL\GraphQL;
use Siler\Http\Request;
use Siler\Http\Response;

require dirname(dirname(__DIR__)).'/vendor/autoload.php';

$pageNumber = Request\get('p', 1);
$rawInput = '
{
    "query": "query { findAllClients(page: '.$pageNumber.') {id guid first last city street zip} }"
}';

try {
    // Retrive the Schema
    $schema = include dirname(__DIR__).'/schema.php';
    
    $input = json_decode($rawInput, true);
    $query = $input['query'];
    $variableValues = isset($input['variables']) ? $input['variables'] : null;

    // Give it to siler
    $rootValue = ['data' => '...'];
    $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
    $output = $result->toArray();
} catch (\Exception $e) {
    $output = [
        'error' => [
            'message' => $e->getMessage()
        ]
    ];
}

Response\header('Content-Type', 'application/json; charset=UTF-8');
Response\json($output);