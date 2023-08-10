<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Traits\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserManController extends Controller
{
    use JsonResponse;

    /**
     * GET user management page
     */
    public function userManPage(Request $request): View
    {
        $userId = Auth::id() ?? Auth::guard('api')->id();
        $user = User::where('id', $userId)->first();
        Log::debug('User open user role management page', ['userId' => $user?->id, 'userName' => $user?->name, 'remoteIp' => $request->ip()]);

        return view('super-pg.userman');
    }

    /**
     * POST request to get user list from table
     */
    public function getUserList(Request $request): HttpJsonResponse
    {
        $userId = Auth::id() ?? Auth::guard('api')->id();
        $user = User::where('id', $userId)->first();
        Log::debug('User is requesting get user list for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        /** Validate Request */
        $validate = Validator::make($request->all(), [
            'username' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $data = User::with(['permissions', 'roles'])
            ->when($validated['username'] ?? false, function (Builder|QueryBuilder $query, $username) {
                $query->where('username', 'ILIKE', '%'.$username.'%');
            })->when($validated['name'] ?? false, function (Builder|QueryBuilder $query, $name) {
                $query->where('name', 'ILIKE', '%'.$name.'%');
            })->orderBy('username')->get();

        return response()->json($data);
    }

    /**
     * POST request to get roles and permissions form table
     */
    public function getUserRolePerm(Request $request): HttpJsonResponse
    {
        $userId = Auth::id() ?? Auth::guard('api')->id();
        $user = User::where('id', $userId)->first();
        Log::debug('User is requesting get user role and permission for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        return response()->json([
            'roles' => Role::orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    /**
     * POST request user man submit
     */
    public function postUserManSubmit(Request $request): HttpJsonResponse
    {
        $userId = Auth::id() ?? Auth::guard('api')->id();
        $user = User::where('id', $userId)->first();
        Log::debug('User is requesting submit user role and permission for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        /** Validate Request */
        $validate = Validator::make($request->all(), [
            'type_create' => ['required', 'boolean'],
            'id' => ['required_if:type_create,false', 'string', 'exists:App\Models\User,id'],
            'name' => ['required', 'string'],
            'username' => ['required', 'string'],
            'roles' => ['required_if:permissions,null', 'array'],
            'permissions' => ['required_if:roles,null', 'array'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        (bool) $isRestored = false;

        DB::beginTransaction();
        try {
            if ($validated['type_create']) {
                $user = User::withTrashed()->where('username', $validated['username'])->first() ?? new User();
                $user->username = $validated['username'];
                $user->name = $validated['name'];
                $user->password = Hash::make(config('auth.defaults.reset_password_data'));
                $user->save();

                if ($user->trashed()) {
                    $user->restore();
                    (bool) $isRestored = true;
                }
            } else {
                $user = User::where('id', $validated['id'])->first();

                /** Check if username is exists */
                $checkUsername = User::withTrashed()->where('username', $validated['username'])->exists();
                if ($checkUsername && $user->username != $validated['username']) {
                    throw ValidationException::withMessages(['username' => 'Username already exists']);
                }

                $user->username = $validated['username'];
                $user->name = $validated['name'];
                $user->save();
            }

            $user->syncRoles($validated['roles']);
            $user->syncPermissions($validated['permissions']);

            DB::commit();

            Log::notice('User successfully submit user role and permission for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('User failed submit user role and permission for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip(), 'error' => $e->getMessage()]);
            throw $e;
        }

        if ($isRestored) {
            return $this->jsonSuccess('User restored successfully', 'User restored successfully');
        } else {
            return $this->jsonSuccess('User saved successfully', 'User saved successfully');
        }
    }

    /**
     * POST request delete user man submit
     */
    public function postDeleteUserManSubmit(Request $request): HttpJsonResponse
    {
        $userId = Auth::id() ?? Auth::guard('api')->id();
        $user = User::where('id', $userId)->first();
        Log::debug('User is requesting delete user for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        /** Validate Request */
        $validate = Validator::make($request->all(), [
            'id' => ['required', 'string', 'exists:App\Models\User,id'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $user = User::where('id', $validated['id'])->first();
        $user->delete();

        Log::warning('User successfully delete user for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        return $this->jsonSuccess('User deleted successfully', 'User deleted successfully');
    }

    /**
     * POST request reset password user man submit
     */
    public function postResetPasswordUserManSubmit(Request $request): HttpJsonResponse
    {
        $userId = Auth::id() ?? Auth::guard('api')->id();
        $user = User::where('id', $userId)->first();
        Log::debug('User is requesting reset password user for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        /** Validate Request */
        $validate = Validator::make($request->all(), [
            'id' => ['required', 'string', 'exists:App\Models\User,id'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $user = User::where('id', $validated['id'])->first();
        $user->password = Hash::make(config('auth.defaults.reset_password_data'));
        $user->save();

        Log::warning('User successfully reset password user for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        return $this->jsonSuccess('User password reset successfully', 'User password reset successfully');
    }
}
