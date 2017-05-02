<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class OperationsController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /operations
     *
     * @return array
     */
    public function index()
    {
        $ops = Operation::whereUserId(Auth::id())->get();

        return ['status' => 'success', 'data' => $ops];
    }


    /**
     * Store a newly created resource in storage.
     * POST /operations
     *
     * @return array
     */
    public function store()
    {
        $inputs = Input::only('sum', 'comment', 'category_id', 'tr_date');
        extract($inputs);

        if (!isset($sum) || !isset($comment) || !isset($category_id) || !isset($tr_date)) {
            return ['status' => 'Error'];
        }

        $user = Auth::user();

        $op = new Operation;
        $op->sum = $sum;

        $user->balance += $sum;
        $user->save();

        $op->comment = $comment;
        $op->category_id = $category_id;
        $op->tr_date = $tr_date;
        $op->user_id = Auth::id();
        $op->save();

        return ['status' => 'success', 'id' => $op->id];
    }


    public function synch()
    {
        $ops = json_decode(Input::only('data')['data']);
        if (count($ops) > 0) {
            Operation::whereUserId(Auth::ID())->delete();
            foreach ($ops as $op) {
                $nop = new Operation;
                $nop->comment = $op->comment;
                $nop->sum = $op->sum;
                $nop->category_id = $op->category_id;
                $nop->tr_date = $op->tr_date;
                $nop->user_id = Auth::id();
                $nop->save();
            }
        } else {
            return ['status' => 'Error'];
        }
        $new_ops = Operation::whereUserId(Auth::id())->get();
        return ['status' => 'success', 'data' => $new_ops];
    }

}