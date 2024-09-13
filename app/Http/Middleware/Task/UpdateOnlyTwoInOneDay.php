<?php

namespace App\Http\Middleware\Task;

use App\Models\Task;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateOnlyTwoInOneDay
{
    public function __construct()
    {
        
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        if($request->input('status') != 'end') {
            return $next($request);
        }

        $todayClosedTasks = Task::whereDate('updated_at', Carbon::today())
             ->where('status', 'end')
             ->get()->count();

    
            
        if($todayClosedTasks > 2) {
           return response()->json(['status' => 'error', 'message' => 'Only two task can be close in oneday'], 400);
        }

        return $next($request);
    }
}
