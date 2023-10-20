<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\LogActivity;
use App\Http\Controllers\Controller;

class UserLogActivityController extends Controller
{
    public function index()
    {
        $logs = \App\Helpers\LogActivity::logActivityLists();
        return view('Admin.user_log.index', compact('logs'));
    }

    public function store(Request $request)
    {
        $userId = auth()->check() ? auth()->user()->id : null;
        \App\Helpers\LogActivity::addToLog('User Logged In', $request, $userId);
        
        return redirect()->route('admin.logActivity');     
    }


    public function destroy($id)
    {
        $log = LogActivity::find($id);

        if ($log) {
            $log->delete();
            return redirect()->route('admin.logActivity')->with('success', 'Log deleted successfully');
        } else {
            return redirect()->route('admin.logActivity')->with('error', 'Log not found');
        }
    }

    public function show($id)
    {
        $log = LogActivity::find($id);

        if ($log) {
            return view('admin.user_log.show', compact('log'));
        } else {
            return redirect()->route('admin.logActivity')->with('error', 'Log not found');
        }
    }
}