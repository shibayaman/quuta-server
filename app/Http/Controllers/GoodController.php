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

    public function destroy(Request $request)
    {
        $request->validate(['post_id' => 'required|integer|exists:posts']);

        $good = Good::where([
            'post_id' => $request->post_id,
            'user_id' => Auth::id()
        ])->first();

        abort_unless($good, 422, 'good does not exists for givven post_id');

        DB::transaction(function () use ($good) {
            $good->delete();
        });

        return response()->json('', 204);
    }
}
