<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(protected UserService $userService)
    {

    }

    /**
     * show users list
     *@param FilterRequest  $request
     * @return JsonResponse
     *
     */
    public function getUsers(FilterRequest $request): JsonResponse
    {
        $users = $this->userService->listUsers($request->validated());
        return response()->json($users);
    }
}
