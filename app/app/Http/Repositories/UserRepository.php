<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository
{
    public function create($lastName, $name, $middleName, $phone, $email)
    {
        return User::createOrFirst(
            ['phone' => $phone],
            ['last_name' => $lastName, 'name' => $name, 'middle_name' => $middleName, 'email' => $email]
        );
    }

    public function list(): ?Collection
    {
        return User::all();
    }

    public function deletedList(): ?Collection
    {
        return User::onlyTrashed()->get();
    }

    public function get(string $id): ?User
    {
        return User::whereId($id)->withTrashed()->first();
    }

    public function update(string $id, array $data): ?User
    {
        $user = $this->get($id);

        $user->update([
            'name' => $data['name'] ?? $user->name,
            'last_name' => $data['lastName'] ?? $user->last_name,
            'middle_name' => $data['middleName'] ?? $user->middle_name,
            'email' => $data['email'] ?? $user->email,
        ]);

        return $user->fresh();
    }

    public function destroy(string $id): void
    {
        User::find($id)->forceDelete();
    }

    public function delete(string $id): User
    {
        User::find($id)->delete();
        return User::withTrashed()->find($id);
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

    public function restore(string $id): User
    {
        User::withTrashed()->find($id)->restore();

        return User::find($id);
    }

    public function bulkRestore(array $ids): void
    {
        foreach ($ids as $id) {
            $this->restore($id);
        }
    }
}
