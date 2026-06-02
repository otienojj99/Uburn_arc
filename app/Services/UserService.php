<?php

namespace App\Services;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * Create a new user.
     */

    public function createUser(array $data): User
    {
        // Merge created_by from authenticated user
        $data['created_by'] = Auth::id();
        $data['password'] = Hash::make($data['password']);
        // Set default values if not provided

        $data['is_active'] = $data['is_active'] ?? true;

        return User::create($data);
    }

    /**
     * Update an existing user.
     */

    public function updateUser(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $data['updated_by'] = Auth::id();

        $user->update($data);
        return $user->fresh();
    }

    /**
     * Toggle user active status.
     */

    public function toggleActive(User $user): bool
    {
        $user->is_active = !$user->is_active;
        $user->updated_by = Auth::id();
        return $user->save();
    }
}
