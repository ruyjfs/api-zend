<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\GraphQL;

class GraphQLHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        try {
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'echo' => [
                        'type' => Type::string(),
                        'args' => [
                            'message' => ['type' => Type::string()],
                        ],
                        'resolve' => function ($root, $args) {
                            return $root['prefix'] . $args['message'];
                        }
                    ],
                ],
            ]);
            $mutationType = new ObjectType([
                'name' => 'Calc',
                'fields' => [
                    'sum' => [
                        'type' => Type::int(),
                        'args' => [
                            'x' => ['type' => Type::int()],
                            'y' => ['type' => Type::int()],
                        ],
                        'resolve' => function ($root, $args) {
                            return $args['x'] + $args['y'];
                        },
                    ],
                ],
            ]);
            // See docs on schema options:
            // http://webonyx.github.io/graphql-php/type-system/schema/#configuration-options
            $schema = new Schema([
                'query' => $queryType,
                'mutation' => $mutationType,
            ]);
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = isset($input['variables']) ? $input['variables'] : null;
            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();
            return new JsonResponse($output);
        } catch (\Exception $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage()
                ]
            ];
            return new JsonResponse($output);
        }
    }
}
