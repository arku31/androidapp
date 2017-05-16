<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function store(Request $request)
    {
        $validation = $this->validate($request, [
           'price' => 'numeric|required',
           'name' => 'required',
           'type' => 'required',
        ]);
        if ($validation !== null) {
            return response()->json([
                'status' => 'error',
                'validation_rules' => $validation
            ]);
        }
        $item = new Item();
        $item->price = $request->price;
        $item->name = $request->name;
        $item->type = $request->type;
        $item->user_id = Auth::id();
        $item->save();
        return response(['status' => 'success', 'id' => $item->id]);
    }

    public function destroy(Request $request)
    {
        $validation = $this->validate($request, [
            'id' => 'numeric|required',
        ]);
        if ($validation !== null) {
            return response()->json([
                'status' => 'error',
                'validation_rules' => $validation
            ]);
        }
        Item::destroy($request->id);
        return response(['status' => 'success', 'id' => $request->id]);
    }

    public function itemsByType(Request $request)
    {

        $validation = $this->validate($request, [
            'type' => 'required'
        ]);
        if ($validation !== null) {
            return response()->json([
                'status' => 'error',
                'validation_rules' => $validation
            ]);
        }
        return Item::where('type', $request->type)
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')->get();
    }

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            return $rules;
        }
        return null;
    }

}
