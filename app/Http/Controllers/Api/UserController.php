<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponseTrait;

class UserController extends Controller
{

    use ApiResponseTrait;

    public function __construct(protected UserService $userService) {}
    /**
     * Display a listing of the resource.
     */

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        //
        $user = User::with('warehouse')
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->paginate($request->per_page ?? 15);

        return $this->successResponse(
            UserResource::collection($user)->response()->getData(true)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
        $user = $this->userService->createUser($request->validated());

        return $this->successResponse(
            new UserResource($user),
            'User created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return $this->successResponse(
            new UserResource($user->load('warehouse', 'creator'))
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
        $data = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'first_name'    => 'nullable|string|max:255',
            'last_name'     => 'nullable|string|max:255',
            'email'         => 'sometimes|email|unique:users,email,' . $user->id,
            'phone'         => 'nullable|string|max:20',
            'role'          => 'sometimes|in:super_admin,admin1,admin2,user',
            'warehouse_id'  => 'nullable|exists:warehouses,id',
            'password'      => 'nullable|confirmed|min:8',
            'is_active'     => 'sometimes|boolean',
        ]);

        $user = $this->userService->updateUser($user, $data);

        return $this->successResponse(
            new UserResource($user),
            'User updated successfully.'
        );
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user)
    {
        $this->userService->toggleActive($user);
        return $this->successResponse(
            new UserResource($user),
            'User status toggled successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        // Soft delete if you're using SoftDeletes
        $user->delete();
        return $this->successResponse(null, 'User deleted successfully.');
    }
}
