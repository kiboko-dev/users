<?php

namespace App\Http\Controllers;

use App\Helpers\Responses;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes as SA;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('Регистрация|Авторизация')
]
class AuthController extends Controller
{
    use Responses;
    public function __construct(protected AuthService $authService)
    {
    }

    #[
        SA\Endpoint(
            title: 'Авторизация',
            description: 'Проверяет номер телефона и код из СМС. Так как отправка СМС не реализована,
                           в качестве правильного кода принимает последние 4 цифры номера телефона.'
        ),
        SA\ResponseFromApiResource(
            name: TokenResource::class,
            model: User::class,
            status: Response::HTTP_OK,
            description: 'Возвращает токены для авторизации и время протухания токена'
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function login(UserLoginRequest $request): JsonResponse
    {
        $tokenData = $this->authService->login(
            phone: $request->validated('phone'),
            code: $request->validated('code') ?? null
        );

        return ($tokenData['access_token'])
            ? $this->successResponseWithData(TokenResource::make($tokenData))
            : $this->successResponseWithData('', 200);
    }

    #[
        SA\Endpoint(
            title: 'Регистрация',
            description: 'Регистрирует пользователя и возвращает его данные'
        ),
        SA\ResponseFromApiResource(
            name: UserResource::class,
            model: User::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function registration(UserRegisterRequest $registerRequest): JsonResponse
    {
        $user = $this->authService->registration(
            phone: $registerRequest->validated('phone'),
            lastName: $registerRequest->validated('lastName'),
            name: $registerRequest->validated('name'),
            middleName: $registerRequest->validated('middleName'),
            email: $registerRequest->validated('email'),
        );

        return response()->json(UserResource::make($user));
    }

    #[
        SA\Endpoint(
            title: 'Выход',
            description: 'Убивает токен авторизации'
        ),
        SA\Response(
            content: 'OK',
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
