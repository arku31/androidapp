<?php
namespace App\Http\Controllers;
use App\Item;
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
        $total_expenses = 0;
        $total_income = 0;

        Item::where('type', 'expense')->get()->each( function($item) use (&$total_expenses){
            $total_expenses+=$item->price;
        });
        Item::where('type', 'income')->get()->each( function($item) use (&$total_income){
            $total_income+=$item->price;
        });
        return [
            'status' => 'success',
            'balance' => $user->balance,
            'total_income' => $total_income,
            'total_expenses' => $total_expenses
        ];
    }
}