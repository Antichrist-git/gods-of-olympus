<article class="card god-card h-100">
                            <a class="card-art" title="{{ $god->name }}" href="{{ route('gods.show', [$god, 'from' => request()->getRequestUri()]) }}"
                                aria-label="{{ $god->name }} — подробности">
                                @if ($god->image_path)
                                    <img src="{{ asset('storage/' . $god->image_path) }}" class="god-image"
                                    alt="{{ $god->name }}" loading="lazy" width="1200" height="800">@else<div
                                        class="image-placeholder" aria-hidden="true">Ω</div>
                                @endif
                                <span class="card-number">{{ $god->type }}</span><span
                                    class="card-art-arrow" aria-hidden="true">↗</span>
                            </a>
                            <div class="card-body d-flex flex-column">
                                
                                <h3 class="card-title"><a href="{{ route('gods.show', [$god, 'from' => request()->getRequestUri()]) }}">{{ $god->name }}</a>
                                </h3>
                                <p class="card-text">{{ $god->short_description }}</p>
                                <p class="detail-date">@if ($god->user)<a href="{{ route('collections.show', $god->user) }}">{{ $god->user->name }}</a> · @endif{{ $god->published_at?->format('d.m.Y') }} @if ($god->created_at?->isSameDay($god->published_at ?? now())){{ $god->created_at->format('H:i') }}@endif</p>
                                <div class="card-actions mt-auto"><a class="card-more js-god-detail"
                                        href="{{ route('gods.show', [$god, 'from' => request()->getRequestUri()]) }}"
                                        @can('manage-god', $god) data-edit-url="{{ route('gods.edit', [$god, 'from' => request()->getRequestUri()]) }}" data-delete-url="{{ route('gods.destroy', [$god, 'from' => request()->getRequestUri()]) }}" @endcan>Быстрый просмотр <span
                                            aria-hidden="true">↗</span></a>
                                    @can('manage-god', $god)
                                    <a class="card-edit"
                                        href="{{ route('gods.edit', [$god, 'from' => request()->getRequestUri()]) }}"
                                        aria-label="Редактировать: {{ $god->name }}" title="Редактировать">✎</a>
                                    @endcan
                                </div>
                            </div>
                        </article>
