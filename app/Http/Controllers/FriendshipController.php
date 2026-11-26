<?php

namespace App\Http\Controllers;

use App\Events\FriendAdded;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendshipController extends Controller
{
    public function store(Request $request, User $user): RedirectResponse
    {
        $viewer = $request->user();
        abort_if($viewer->is($user), 422);

        DB::transaction(function () use ($viewer, $user) {
            $added = DB::table('friendships')->insertOrIgnore([
                'user_id' => $viewer->id, 'friend_id' => $user->id,
            ]);
            if ($added) {
                event(new FriendAdded($viewer->id, $user->id));
            }
        });

        return redirect()->route('collections.show', $user);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $viewer = $request->user();
        abort_if($viewer->is($user), 422);
        DB::transaction(function () use ($viewer, $user) {
            $viewer->friends()->detach($user->id);
            $user->friends()->detach($viewer->id);
        });

        return redirect()->route('collections.show', $user);
    }
}
