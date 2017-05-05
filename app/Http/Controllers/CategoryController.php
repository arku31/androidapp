<?php
namespace App\Http\Controllers;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /category
     *
     * @return array
     */
    public function index()
    {
        $cats = Category::whereUserId(Auth::id())->get();//lists('title', 'id');

        return array('status' => 'success', 'data' => $cats);
    }


    /**
     * Store a newly created resource in storage.
     * POST /category
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $title = $request->title;

        if (empty($title)) {
            return ['status' => 'Error'];
        }

        $cat = new Category;
        $cat->title = $title;
        $cat->user_id = Auth::id();
        $cat->save();

        return ['status' => 'success', 'data' => $cat];
    }

    /**
     * Display the specified resource.
     * GET /categories/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return Category::with('transactions')->whereId($id)->get();
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function edit(Request $request)
    {
        $id = $request->input('id');
        $title = $request->title;
        if (empty($id) || empty($title)) {
            return ['status' => 'Error'];
        }
        if (Category::find($id) == null) {
            return ['status' => 'Wrong id'];
        }

        $cat = Category::find($id);
        $cat->title = $title;
        $cat->save();

        return ['status' => 'success', 'data' => $cat];
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        if (Category::find($id) == null) {
            return ['status' => 'Wrong id'];
        }

        return ['status' => 'success', 'data' => Category::destroy($id)];
    }


    public function synch(Request $request)
    {
        $cats = json_decode($request->data);
        if (count($cats) > 0) {
            $old_cats = Category::whereUserId(Auth::ID())->delete();
            foreach ($cats as $cat) {
                $nc = new Category;
                $nc->title = $cat->title;
                $nc->id = $cat->id;
                $nc->user_id = Auth::id();
                $nc->save();
            }
        } else {
            return ['status' => 'Error'];
        }

        $new_cats = Category::whereUserId(Auth::id())->get();
        return array('status' => 'success', 'data' => $new_cats);
    }

    public function transcat()
    {
        $cats = Category::with('transactions')->whereUserId(Auth::id())->get();
        return $cats;
    }
}