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
        DB::transaction(function () {
            Good::create([
                'post_id' => request('post_id'),
                'user_id' => Auth::id()
            ]);
        });

        return response()->json(['message' => 'OK'], 200);
    }

    public function destroy(Good $good)
    {
        $this->authorize('delete-good', $good);

        DB::transaction(function () use ($good) {
            $good->delete();
        });

        return response()->json(['message' => 'OK'], 200);
    }
}
