<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserCheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 在這裡編寫您的限制邏輯
        // 檢查使用者的權限或角色
        // 例如，如果使用者沒有特定權限，則重定向到錯誤頁面
        if (Auth::user()->hasRole('admin')) {
            // return redirect()->route('error_page');
            return $next($request);
        }
        return abort(404);
        // return response('对不起，您没有权限访问此页面。', 403);
    }
}
