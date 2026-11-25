<div class="d-flex flex-wrap gap-2 mt-2">
    <form method="POST" action="{{ route('archive.restore', $god->id) }}">
        @csrf
        @method('PATCH')
        <button class="btn btn-sm btn-outline-primary">Восстановить</button>
    </form>
    <form method="POST" action="{{ route('archive.destroy', $god->id) }}"
          onsubmit="return confirm('Окончательно удалить эту карточку? Восстановить её будет невозможно.')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-outline-danger">Удалить навсегда</button>
    </form>
</div>
