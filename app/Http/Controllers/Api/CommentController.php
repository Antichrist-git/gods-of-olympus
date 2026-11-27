<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return CommentResource::collection($this->comments($request)->latest('id')->paginate(20));
    }

    public function show(Request $request, int $id): CommentResource
    {
        return new CommentResource($this->comments($request)->findOrFail($id));
    }

    private function comments(Request $request): Builder
    {
        $friends = $request->user()->friends()->select('users.id');

        return Comment::query()->whereHas('god')->with('god:id,name,type')
            ->withExists([
                'user as is_friend' => fn (Builder $author) => $author->whereIn('users.id', $friends),
            ]);
    }
}
