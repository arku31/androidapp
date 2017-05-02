<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UsersController extends Controller
{

    public function balance()
    {
        $balance = Input::only('set');
        $user = Auth::user();
        if (isset($balance) && isset($balance['set'])) {
            $user->balance = $balance['set'];
            $user->save();
        }
        return ['status' => 'success', 'balance' => $user->balance];
    }
}