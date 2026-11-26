<?php

namespace App\Models;

use App\Support\GodName;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class God extends Model
{
    use SoftDeletes;

    protected static function booted(): void
    {
        // Проверяем права и при записи через Eloquent вне контроллера.
        $checkOwner = static function (God $god): void {
            Gate::authorize('manage-god', $god);
        };
        // Обе операции доступны только администратору и только после мягкого удаления.
        $checkArchived = static function (God $god): void {
            Gate::authorize('manage-archive');
            abort_unless($god->trashed(), 409);
        };
        static::restoring($checkArchived);
        static::forceDeleting($checkArchived);
        static::updating($checkOwner);
        static::deleting($checkOwner);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'name', 'type', 'short_description', 'description', 'fact', 'image_path', 'published_at',
    ];

    // При редактировании исключаем саму карточку из поиска совпадений.
    public static function nameIsTaken(string $name, ?int $currentId = null): bool
    {
        $query = static::query()->where('name_key', GodName::key($name));
        if ($currentId !== null) {
            $query->whereKeyNot($currentId);
        }
        return $query->exists();
    }

    protected function casts(): array
    {
        return ['published_at' => 'date'];
    }

    // Записываем одновременно отображаемое имя и его уникальный поисковый ключ.
    // Работает также при создании через Tinker, не только через веб-форму.
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => [
                'name' => GodName::normalize((string) $value),
                'name_key' => GodName::key((string) $value),
            ],
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value) : null,
            set: fn ($value) => $value ? Carbon::parse($value)->toDateTimeString() : null,
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value) : null,
            set: fn ($value) => $value ? Carbon::parse($value)->toDateTimeString() : null,
        );
    }
}
