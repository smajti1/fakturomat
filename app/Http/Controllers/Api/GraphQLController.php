<?php


namespace App\Http\Controllers\Api;


use App\Graphql\Schema;
use App\Http\Controllers\Controller;
use GraphQL\GraphQL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class GraphQLController extends Controller
{
    private $request;
    private $schema;

    public function __construct(Request $request, Schema $schema)
    {
        $this->request = $request;
        $this->schema = $schema;
    }

    public function execute()
    {
        $query = $this->request->get('query');
        $variableValues = $this->request->get('variables', null);
        $schema = $this->schema->createSchema();

        try {
            $result = GraphQL::executeQuery($schema, $query, null, null, $variableValues);
            $output = $result->toArray();
        } catch (\Exception $e) {
            $output = [
                'errors' => [
                    [
                        'message' => $e->getMessage()
                    ]
                ]
            ];
        }

        return Response::json($output, 200);
    }
}