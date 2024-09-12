<?php

namespace App\Http\Controllers;

use App\Helpers\Responses;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\UserDeleteDestroyRequest;
use App\Http\Requests\UserRestoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes as SA;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('Пользователи')
]
class UserController extends Controller
{
    use Responses;

    public function __construct(
        protected UserRepository $repository,
        protected UserService    $service
    ) {
    }

    #[
        SA\Endpoint(
            title: 'Список пользователей',
            description: 'Возвращает список активных пользователей'
        ),
        SA\Subgroup('Списки'),
        SA\ResponseFromApiResource(
            name: UserResource::class,
            model: User::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function index(): JsonResponse
    {
        return $this->successResponseWithData(
            UserResource::collection($this->repository->list())
        );
    }

    #[
        SA\Endpoint(
            title: 'Показать пользователя',
            description: 'Возвращает информацию о пользователе'
        ),
        SA\Subgroup('Списки'),
        SA\ResponseFromApiResource(
            name: UserResource::class,
            model: User::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[SA\UrlParam(
        name: 'id',
        type: 'uuid',
        required: true,
        example: '5db01bb1-5667-4364-a1ec-a3ea28083522'
    )]
    public function show(string $id): JsonResponse
    {
        return $this->successResponseWithData(
            UserResource::make($this->repository->get($id))
        );
    }

    #[
        SA\Endpoint(
            title: 'Редактировать пользователя',
            description: 'Изменяет доступные свойства пользователя'
        ),
        SA\Subgroup('Действия'),
        SA\ResponseFromApiResource(
            name: UserResource::class,
            model: User::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[SA\UrlParam(
        name: 'id',
        type: 'uuid',
        required: true,
        example: '5db01bb1-5667-4364-a1ec-a3ea28083522'
    )]
    public function update(string $id, UserUpdateRequest $request): JsonResponse
    {
        return $this->successResponseWithData(
            UserResource::make($this->service->update($id, $request->validated()))
        );
    }

    #[
        SA\Endpoint(
            title: 'Удалить пользователя полностью',
            description: 'Удаляет пользователя из БД'
        ),
        SA\Subgroup('Действия'),
        SA\Response(content: '', status: Response::HTTP_OK, ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[SA\UrlParam(
        name: 'id',
        type: 'uuid',
        required: true,
        example: '5db01bb1-5667-4364-a1ec-a3ea28083522'
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->service->destroy($id);

        return $this->successResponse();
    }

    #[
        SA\Endpoint(
            title: 'Корзина пользователей',
            description: 'Возвращает список удалённых пользователей'
        ),
        SA\Subgroup('Списки'),
        SA\ResponseFromApiResource(
            name: UserResource::class,
            model: User::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function deletedList(): JsonResponse
    {
        return $this->successResponseWithData(
            UserResource::collection($this->repository->deletedList())
        );
    }

    #[
        SA\Endpoint(
            title: 'Удаление пользователя в корзину',
            description: 'Мягкое удаление пользователя с возможностью восстановления'
        ),
        SA\Subgroup('Действия'),
        SA\Response(content: '', status: Response::HTTP_OK),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[SA\UrlParam(
        name: 'id',
        type: 'uuid',
        required: true,
        example: '5db01bb1-5667-4364-a1ec-a3ea28083522'
    )]
    public function delete(string $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->successResponse();
    }

    #[
        SA\Endpoint(
            title: 'Восстановить пользователя',
            description: 'Восстановление пользователя из корзины'
        ),
        SA\Subgroup('Действия'),
        SA\Response(content: '', status: Response::HTTP_OK),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[SA\UrlParam(
        name: 'id',
        type: 'uuid',
        required: true,
        example: '5db01bb1-5667-4364-a1ec-a3ea28083522'
    )]
    public function restore(string $id): JsonResponse
    {
        $this->service->restore($id);

        return $this->successResponse();
    }

    #[
        SA\Endpoint(
            title: 'Удалить несколько пользователей',
            description: 'Удаляет нескольких пользователей в корзину с возможностью восстановления'
        ),
        SA\Subgroup('Массовые действия'),
        SA\Response(content: '', status: Response::HTTP_OK),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function bulkDelete(UserDeleteDestroyRequest $request): JsonResponse
    {
        $this->service->bulkDelete($request->validated('ids'));

        return $this->successResponse();
    }

    #[
        SA\Endpoint(
            title: 'Удалить несколько пользователей полностью',
            description: 'Удаляет полностью из БД несколько пользователей'
        ),
        SA\Subgroup('Массовые действия'),
        SA\Response(content: '', status: Response::HTTP_OK),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function bulkDestroy(UserDeleteDestroyRequest $request): JsonResponse
    {
        $this->service->bulkDestroy($request->validated('ids'));

        return $this->successResponse();
    }

    #[
        SA\Endpoint(
            title: 'Восстановить несколько пользователей',
            description: 'Восстанавливает несколько пользователей из корзины'
        ),
        SA\Subgroup('Массовые действия'),
        SA\Response(content: '', status: Response::HTTP_OK),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function bulkRestore(UserRestoreRequest $request): JsonResponse
    {
        $this->service->bulkRestore($request->validated('ids'));

        return $this->successResponse();
    }
}
