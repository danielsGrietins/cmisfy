<?php

namespace Cmsify\Cmsify\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);

        $token = auth('cmsify-api')->attempt($credentials);
        if (!$token) {
            return response()->json(['message' => 'The given data was invalid.'], 422);
        }

        $userModel = config('auth.providers.users.model');
        $user = $userModel::whereEmail($request->get('email'))->first();
        if (!$user->hasPermissionTo('access Admin')) {
            return response()->json(['message' => 'The given data was invalid.'], 422);
        }

        return $this->respondWithToken($token, $user);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $user = auth('cmsify-api')->user();
        $token = auth('cmsify-api')->refresh();

        return $this->respondWithToken($token, $user);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('cmsify-api')->logout();

        return response()->json(null, 204);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function profile(Request $request)
    {
        $user = $request->user('cmsify-api');

        return $this->getUserProfile($user);
    }

    /**
     * @param string $token
     * @param Model $user
     * @return JsonResponse
     */
    private function respondWithToken(string $token, Model $user): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('cmsify-api')->factory()->getTTL() * 60,
            'user'         => $this->getUserProfile($user)
        ]);
    }

    /**
     * @param Model $user
     * @return array
     */
    private function getUserProfile(Model $user): array
    {
        return [
            'id'       => $user->id,
            'name'     => $user->name,
            'initials' => $this->getInitials($user),
        ];
    }

    /**
     * @param Model $user
     * @return string
     */
    private function getInitials(Model $user): string
    {
        $explodeName = explode(' ', $user->name);
        $firstLetterOfTheWord = array_map(function (string $word) {
            return $word[0];
        }, $explodeName);

        $firstTwoLetters = array_slice($firstLetterOfTheWord, 0, 2);

        return implode($firstTwoLetters);
    }
}
