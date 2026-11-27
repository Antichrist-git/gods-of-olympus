<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GodResource;
use App\Models\God;
use App\Services\GodWriter;
use Illuminate\Http\JsonResponse;
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

    public function store(Request $request, GodWriter $writer): JsonResponse
    {
        $god = $writer->create($request);

        return $this->show($request, $god->id)->response()->setStatusCode(201);
    }

    public function update(Request $request, God $god, GodWriter $writer): GodResource
    {
        $writer->update($request, $god);

        return $this->show($request, $god->id);
    }

    private function cards(Request $request): Builder
    {
        $friends = $request->user()->friends()->select('users.id');

        return God::query()->withExists([
            'user as is_friend' => fn (Builder $owner) => $owner->whereIn('users.id', $friends),
        ]);
    }
}
