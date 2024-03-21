<?php

namespace App\Http\Controllers;

use App\Interfaces\InterfaceClass;
use App\Interfaces\MenuItemClass;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Traits\JsonResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        $user = Auth::user() ?? Auth::guard('api')->user();
        Log::debug('User open user role management page', ['userId' => $user?->id, 'userName' => $user?->name, 'remoteIp' => $request->ip()]);

        return view('super-pg.userman', [
            'expandedKeys' => MenuItemClass::currentRouteExpandedKeys($request->route()->getName()),
        ]);
    }

    /**
     * POST request to get user list from table
     */
    public function getUserList(Request $request): HttpJsonResponse
    {
        $user = Auth::user() ?? Auth::guard('api')->user();
        Log::debug('User is requesting get user list for User Role Management', ['userId' => $user?->id, 'uwserName' => $user?->name, 'apiUserIp' => $request->ip()]);

        $data = User::with(['roles' => function ($query) {
            return $query->orderBy('name');
        }, 'permissions' => function ($query) {
            return $query->orderBy('is_const', 'desc')->orderBy('name');
        }])->orderBy('username')->get()->map(function (User $user) {
            return collect($user)->merge([
                'roles_array' => Cache::tags([Role::class])->remember(Role::class.'-getRoles-'.$user->id, Carbon::now()->addYear(), function () use ($user) {
                    return $user->getRoleNames()->sortBy('name');
                }),
                'permissions_array' => Cache::tags([Permission::class])->remember(Permission::class.'-getPermissions-'.$user->id, Carbon::now()->addYear(), function () use ($user) {
                    return $user->getAllPermissions()->sortBy([
                        ['is_const', 'desc'],
                        ['is_menu', 'asc'],
                        ['name', 'asc'],
                    ])->pluck('name');
                }),
            ]);
        });

        return response()->json($data);
    }

    /**
     * POST request to get roles and permissions form table
     */
    public function getUserRolePerm(Request $request): HttpJsonResponse
    {
        $user = Auth::user() ?? Auth::guard('api')->user();
        Log::debug('User is requesting get user role and permission for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        return response()->json([
            'roles' => Cache::tags([Role::class])->remember(Role::class.'-orderBy-name', Carbon::now()->addYear(), function () {
                return Role::orderBy('name')->get();
            }),
            'permissions' => Cache::tags([Permission::class])->remember(Permission::class.'-orderBy-name', Carbon::now()->addYear(), function () {
                return Permission::orderBy('is_const', 'desc')->orderBy('name')->get();
            }),
            'permissions_const' => Cache::tags([Permission::class])->remember(Permission::class.'-const-orderBy-name', Carbon::now()->addYear(), function () {
                return Permission::whereIn('name', InterfaceClass::ALLPERM)->orderBy('name')->get();
            }),
        ]);
    }

    /**
     * POST request user man submit
     */
    public function postUserManSubmit(Request $request): HttpJsonResponse
    {
        $user = Auth::user() ?? Auth::guard('api')->user();
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

        $validatedLog = $validated;
        Log::info('User submit user role and permission for User Role Management validation', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip(), 'validated' => $validatedLog]);

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
        $user = Auth::user() ?? Auth::guard('api')->user();
        Log::debug('User is requesting delete user for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        /** Validate Request */
        $validate = Validator::make($request->all(), [
            'id' => ['required', 'string', 'exists:App\Models\User,id'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $validatedLog = $validated;
        Log::info('User delete user for User Role Management validation', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip(), 'validated' => $validatedLog]);

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
        $user = Auth::user() ?? Auth::guard('api')->user();
        Log::debug('User is requesting reset password user for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        /** Validate Request */
        $validate = Validator::make($request->all(), [
            'id' => ['required', 'string', 'exists:App\Models\User,id'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $validatedLog = $validated;
        Log::info('User reset password user for User Role Management validation', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip(), 'validated' => $validatedLog]);

        $user = User::where('id', $validated['id'])->first();
        $user->password = Hash::make(config('auth.defaults.reset_password_data'));
        $user->save();

        Log::warning('User successfully reset password user for User Role Management', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        return $this->jsonSuccess('User password reset successfully', 'User password reset successfully');
    }
}
