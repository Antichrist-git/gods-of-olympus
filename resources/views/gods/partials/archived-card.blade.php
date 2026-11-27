<article class="card god-card h-100">
@if ($god->image_path)<img class="trash-picture" src="{{ asset('storage/'.$god->image_path) }}" alt="{{ $god->name }}" loading="lazy">@endif
<div class="card-body"><h2 class="h4">{{ $god->name }}</h2><p>{{ $god->user?->name }} · Удалено {{ $god->deleted_at->format('d.m.Y H:i') }}</p>
@include('gods.partials.archive-actions')</div></article>
