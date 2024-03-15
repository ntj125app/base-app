<?php

namespace App\Http\Controllers;

use App\Interfaces\MenuItemClass;
use App\Logger\Models\ServerLog;
use App\Traits\JsonResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Monolog\Logger;

class ServerManController extends Controller
{
    use JsonResponse;

    /**
     * GET request to view server logs layouts
     */
    public function serverLogs(Request $request): View
    {
        $user = Auth::user() ?? Auth::guard('api')->user();
        Log::debug('User open server log', ['userId' => $user?->id, 'userName' => $user?->name, 'remoteIp' => $request->ip()]);

        return view('super-pg.serverlog', [
            'expandedKeys' => MenuItemClass::currentRouteExpandedKeys($request->route()->getName()),
        ]);
    }

    /**
     * POST request to get server Logs from tables
     */
    public function getServerLogs(Request $request): HttpJsonResponse
    {
        $user = Auth::user() ?? Auth::guard('api')->user();
        Log::debug('User get server log', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        /** Validate Request */
        $validate = Validator::make($request->all(), [
            'date_start' => ['nullable', 'date', 'before_or_equal:date_end'],
            'date_end' => ['nullable', 'date', 'after_or_equal:date_start'],
            'log_level' => ['nullable', 'string'],
            'log_message' => ['nullable', 'string'],
            'log_extra' => ['nullable', 'string'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $validatedLog = $validated;
        Log::info('User get server log validation', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip(), 'validated' => $validatedLog]);

        $data = ServerLog::when($validated['date_start'] ?? null, function ($query, $date_start) {
            return $query->where('created_at', '>=', Carbon::parse($date_start, 'Asia/Jakarta')->startOfDay());
        })->when($validated['date_end'] ?? null, function ($query, $date_end) {
            return $query->where('created_at', '<=', Carbon::parse($date_end, 'Asia/Jakarta')->endOfDay());
        })->when($validated['log_level'] ?? null, function ($query, $log_level) {
            $log_level === 'all' ? $log_level = 'debug' : $log_level = $log_level;

            return $query->where('level', '>=', Logger::toMonologLevel($log_level));
        })->when($validated['log_message'] ?? null, function ($query, $log_message) {
            return $query->where('message', 'ilike', '%'.$log_message.'%');
        })->when($validated['log_extra'] ?? null, function ($query, $log_extra) {
            return $query->where('context', 'ilike', '%'.$log_extra.'%');
        })->orderBy('id', 'desc')->limit(20000)->get();

        return response()->json($data);
    }

    /**
     * POST request to clear application cache
     */
    public function postClearAppCache(Request $request): HttpJsonResponse
    {
        $user = Auth::user() ?? Auth::guard('api')->user();
        Log::debug('User clear app cache', ['userId' => $user?->id, 'userName' => $user?->name, 'apiUserIp' => $request->ip()]);

        /** Clear Cache */
        Cache::flush();

        return $this->jsonSuccess('Cache cleared', 'Cache cleared successfully');
    }
}
