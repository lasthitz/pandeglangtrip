<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{
    public function show(Request $request)
    {
        // auth sudah dipastikan oleh middleware
        $user = $request->user();

        return view('account.settings', compact('user'));
    }
}
