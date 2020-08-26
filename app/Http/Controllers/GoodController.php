<?php

namespace App\Http\Controllers;

use App\Good;
use App\Http\Requests\StoreGood;
use Auth;
use DB;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\DB;

class GoodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(StoreGood $request)
    {
        //GoodのcreatedフックでPostを更新してるのでここでtransaction開始
        DB::transaction(function () {
            Good::create([
                'post_id' => request('post_id'),
                'user_id' => Auth::id()
            ]);
        });

        return response()->json(['message' => 'OK'], 200);
    }
}
