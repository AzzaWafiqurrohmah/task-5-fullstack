<?php

namespace App;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
    /**
     * Return a success JSON response
     * @param array|string $data
     * @param string $message
     * @param int|null $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data = [], string $message = null, int $code = 200)
    {
        return response()->json([
            'meta' => [
                'code' => $code,
                'status' => 1,
                'message' => $message,
            ],
            'data' => $data,
        ], $code);
    }

    /**
     * Return an error JSON response
     * @param array|string $data
     * @param string $message
     * @param int|null $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($data = [], string $message = null, int $code = 400)
    {
        return response()->json([
            'meta' => [
                'code' => $code,
                'status' => 0,
                'message' => $message,
            ],
            'data' => $data,
        ], $code);
    }

    /**
     * Return json with pagination data
     *
     * @param Illuminate\Pagination\LengthAwarePaginator $pagination
     * @param string $resource
     * @param string $alias
     * @return \Illuminate\Http\JsonResponse
     */
    protected function withPagination(LengthAwarePaginator $pagination, $resource,  $alias = 'items')
    {
        $data = [
            $alias => $resource::collection($pagination->items()),
            'pagination' => [
                'total' => $pagination->total(),
                'perPage' => $pagination->perPage(),
                'currentPage' => $pagination->currentPage(),
                'lastPage' => $pagination->lastPage()
            ]
        ];

        return $this->success($data);
    }
}
