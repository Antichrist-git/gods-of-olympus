<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\God;
use Illuminate\Http\JsonResponse;
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

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'god_id' => ['required', 'integer'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $god = God::findOrFail($data['god_id']);
        $comment = new Comment();
        $comment->body = $data['body'];
        $comment->user()->associate($request->user());
        $god->comments()->save($comment);

        return $this->show($request, $comment->id)->response()->setStatusCode(201);
    }

    public function update(Request $request, int $id): CommentResource
    {
        $comment = $this->comments($request)->findOrFail($id);
        abort_unless($comment->user_id == $request->user()->getKey(), 403);

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);
        $comment->body = $data['body'];
        $comment->save();

        return new CommentResource($comment);
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
