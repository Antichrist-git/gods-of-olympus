<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\God;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, God $god): RedirectResponse
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2000']], [
            'body.required' => 'Введите комментарий.',
            'body.max' => 'Допустимо до 2000 символов.',
        ]);

        // Карточку определяет маршрут, а автора — текущая сессия.
        $comment = new Comment();
        $comment->body = $data['body'];
        $comment->user()->associate($request->user());
        $god->comments()->save($comment);

        return redirect()->to(route('gods.show', $god).'#comment-'.$comment->id);
    }
}
