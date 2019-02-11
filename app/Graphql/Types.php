<?php


namespace App\Graphql;

use App\Graphql\Type\UserType;
use GraphQL\Type\Definition\ObjectType;

class Types
{
    private $type_map = [
        UserType::NAME => UserType::class,
    ];
    private $types = [];

    public function get($name)
    {
        if (!isset($this->types[$name])) {
            if (isset($this->type_map[$name])) {
                $this->types[$name] = new $this->type_map[$name]();
            } else {
                $this->types[$name] = $this->{$name}();
            }
        }
        return $this->types[$name];
    }

    public function Query()
    {
        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'user' => [
                    'type' => $this->get(UserType::NAME),
                    'resolve' => function () {
                        $user = \Auth::user();
                        return $user;
                    }
                ]
            ],
        ]);

        return $queryType;
    }
}