<?php

namespace App\Graphql\Type;

use App\Models\User;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UserType extends ObjectType
{
    const NAME = 'UserType';

    public function __construct()
    {
        $config = [
            'name' => self::NAME,
            'fields' => [
                'email' => [
                    'type' => Type::string(),
                    'resolve' => function (User $user) {
                        return $user->email;
                    }
                ],
            ],
        ];
        parent::__construct($config);
    }
}