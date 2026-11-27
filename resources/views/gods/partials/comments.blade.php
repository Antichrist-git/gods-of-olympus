<section id="comments" class="mt-5" aria-labelledby="comments-heading">
    <h2 id="comments-heading">Комментарии</h2>
    @auth
        <form method="POST" action="{{ route('gods.comments.store', $god) }}" class="mb-4">
            @csrf
            <label class="form-label" for="comment-body">Ваш комментарий</label>
            <textarea id="comment-body" name="body" rows="3" required maxlength="2000"
                      class="form-control @error('body') is-invalid @enderror">{{ old('body') }}</textarea>
            @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <button type="submit" class="btn btn-primary mt-2">Отправить</button>
        </form>
    @else
        <p><a href="{{ route('login') }}">Войдите</a>, чтобы оставить комментарий.</p>
    @endauth
    @forelse ($comments as $comment)
        <article id="comment-{{ $comment->id }}" class="border-top py-3">
            <strong>{{ $comment->user->name }}</strong>
            @if ($friendIds->has($comment->user_id))
                <span class="badge text-bg-primary ms-1">Друг</span>
            @endif
            <time class="small text-secondary ms-2" datetime="{{ $comment->created_at->toAtomString() }}">{{ $comment->created_at->format('d.m.Y H:i') }}</time>
            <p class="preserve-lines mt-2 mb-0">{{ $comment->body }}</p>
            @can('delete-comment')
                <form method="POST" action="{{ route('gods.comments.destroy', [$god, $comment]) }}"
                      onsubmit="return confirm('Удалить этот комментарий без возможности восстановления?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger mt-2">Удалить комментарий</button>
                </form>
            @endcan
        </article>
    @empty
        <p class="text-secondary">Комментариев пока нет.</p>
    @endforelse
    {{ $comments->links('pagination::simple-bootstrap-5') }}
</section>
