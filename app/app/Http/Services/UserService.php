<?php

namespace App\Http\Services;

use App\Enums\ActionEnum;
use App\Http\Repositories\UserRepository;
use App\Models\User;
use App\Traits\LogTrait;
use Illuminate\Support\Facades\DB;

class UserService
{
    use LogTrait;

    public function __construct(protected UserRepository $repository)
    {
    }

    public function update(string $id, array $data): ?User
    {
        return DB::transaction(function () use ($id, $data) {
            $before = $this->repository->get($id);
            $updated = $this->repository->update($id, $data);
            self::logChanges(
                action: ActionEnum::UPDATE,
                after: $updated,
                before: $before
            );

            return $updated;
        });
    }

    public function destroy(string $id): void
    {
        DB::transaction(function () use ($id) {
            $before = $this->repository->get($id);
            $this->repository->destroy($id);
            self::logChanges(
                action: ActionEnum::DESTROY,
                before: $before
            );
        });
    }

    public function delete(string $id): void
    {
        DB::transaction(function () use ($id) {
            $before = $this->repository->get($id);
            $after = $this->repository->delete($id);
            self::logChanges(
                action: ActionEnum::DELETE,
                after: $after,
                before: $before
            );
        });
    }

    public function restore(string $id): void
    {
        DB::transaction(function () use ($id) {
            $before = $this->repository->get($id);
            $after = $this->repository->restore($id);

            self::deleteLog(ActionEnum::RESTORE, $id);
            self::logChanges(
                action: ActionEnum::RESTORE,
                after: $after,
                before: $before
            );
        });
    }

    public function bulkDelete(array $ids): void
    {
        foreach ($ids as $id) {
            $this->delete($id);
        }
    }

    public function bulkDestroy(array $ids): void
    {
        foreach ($ids as $id) {
            $this->destroy($id);
        }
    }

    public function bulkRestore(array $ids): void
    {
        foreach ($ids as $id) {
            $this->restore($id);
        }
    }
}
