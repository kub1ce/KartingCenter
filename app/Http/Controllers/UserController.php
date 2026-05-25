<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::paginate(20);

        if ($request->wantsJson()) {
            return response()->json($users);
        }

        return view('admin.users.index', compact('users'));
    }
}