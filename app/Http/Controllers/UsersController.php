<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UsersController extends Controller
{

    public function balance(Request $request)
    {
        $balance = $request->input('set');
        $user = Auth::user();
        if ($balance) {
            $user->balance = $balance;
            $user->save();
        }
        return ['status' => 'success', 'balance' => $balance];
    }
}