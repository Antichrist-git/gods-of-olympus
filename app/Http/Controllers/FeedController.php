<?php

namespace App\Http\Controllers;

use App\Models\God;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedController extends Controller
{
    public function index(Request $request): View
    {
        $authors = $request->user()->friends()->select('users.id');
        $gods = God::with('user')->whereIn('user_id', $authors)
            ->latest('created_at')->orderByDesc('id')->simplePaginate(20);

        return view('feed.index', compact('gods'));
    }
}
