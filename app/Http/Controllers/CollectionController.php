<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(): View
    {
        return view('collections.index', ['users' => User::orderBy('name')->orderBy('id')->get()]);
    }

    public function show(User $user): View
    {
        return view('collections.show', [
            'owner' => $user,
            'gods' => $user->gods()->orderBy('id')->get(),
        ]);
    }
}
