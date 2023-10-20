<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use App\Models\Admin\LogActivity as LogActivityModel;

class LogActivity
{
    public static function addToLog($subject, Request $request, $userId = null)
    {
        $log = [];
        $log['subject'] = $subject;
        $log['url'] = $request->fullUrl();
        $log['method'] = $request->method();
        $log['ip'] = $request->ip();
        $log['agent'] = $request->header('user-agent');
        $log['user_id'] = $userId ? $userId : 1;  // You can provide a default value

        LogActivityModel::create($log);
    }

    public static function logActivityLists()
    {
        return LogActivityModel::latest()->get();
    }
}