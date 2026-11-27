<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(): View
    {
        return view('collections.index', ['users' => User::withCount('gods')->orderBy('name')->orderBy('id')->get()]);
    }

    public function legacy(int $id): RedirectResponse
    {
        return redirect()->route('collections.show', User::findOrFail($id));
    }

    public function show(User $user): View
    {
        $gods = $user->gods()->with('user')->orderBy('id');
        if (Gate::allows('manage-archive')) {
            $gods->withTrashed();
        }
        return view('collections.show', [
            'owner' => $user,
            'isFriend' => auth()->user()->friends()->whereKey($user->id)->exists(),
            'gods' => $gods->get(),
        ]);
    }
}
