<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GodResource;
use App\Models\God;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GodController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return GodResource::collection($this->cards($request)->orderBy('id')->paginate(20));
    }

    public function show(Request $request, int $id): GodResource
    {
        return new GodResource($this->cards($request)->findOrFail($id));
    }

    private function cards(Request $request): Builder
    {
        $friends = $request->user()->friends()->select('users.id');

        return God::query()->withExists([
            'user as is_friend' => fn (Builder $owner) => $owner->whereIn('users.id', $friends),
        ]);
    }
}
