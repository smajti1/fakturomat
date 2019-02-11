<?php

namespace App\Graphql;

use GraphQL\Type\Schema as GraphqlSchema;

class Schema
{
    public function createSchema(): GraphqlSchema
    {
        $registry = new Types();
        $schema = new GraphqlSchema([
            'query' => $registry->get('Query'),
            'typeLoader' => function ($name) use ($registry) {
                return $registry->get($name);
            }
        ]);
        return $schema;
    }
}