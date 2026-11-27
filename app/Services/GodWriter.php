<?php

namespace App\Services;

use App\Models\God;
use App\Support\GodImage;
use App\Support\GodName;
use App\Support\GodType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GodWriter
{
    public function create(Request $request): God
    {
        $data = $this->validated($request, true);
        $data['image_path'] = GodImage::save($request->file('image'));

        try {
            return $request->user()->gods()->create($data);
        } catch (QueryException $e) {
            Storage::disk('public')->delete($data['image_path']);
            $this->rethrowNameConflict($e);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($data['image_path']);
            throw $e;
        }

    }

    public function update(Request $request, God $god): void
    {
        $data = $this->validated($request, false, $god);
        $oldImage = $god->image_path;
        $newImage = null;

        if ($request->hasFile('image')) {
            $newImage = GodImage::save($request->file('image'));
            $data['image_path'] = $newImage;
        }

        try {
            $god->update($data);
        } catch (QueryException $e) {
            if ($newImage) Storage::disk('public')->delete($newImage);
            $this->rethrowNameConflict($e);
        } catch (\Throwable $e) {
            if ($newImage) Storage::disk('public')->delete($newImage);
            throw $e;
        }

        if ($newImage) GodImage::removeUploaded($oldImage);
    }

    // Единые правила для POST и PUT: сервер никогда не доверяет HTML-валидации.
    private function validated(Request $request, bool $creating, ?God $god = null): array
    {
        if (is_string($request->input('name'))) {
            $request->merge(['name' => GodName::normalize($request->input('name'))]);
        }
        foreach (['type', 'short_description', 'description', 'fact'] as $field) {
            if (is_string($request->input($field))) {
                $request->merge([$field => trim($request->input($field))]);
            }
        }

        $textHasLetters = function (string $attribute, mixed $value, \Closure $fail): void {
            if (is_string($value) && !preg_match('/\p{L}/u', $value)) {
                $fail('Поле «' . $this->attributes()[$attribute] . '» должно содержать осмысленный текст, а не только цифры или знаки.');
            }
        };

        $data = $request->validate([
            'name' => [
                'bail', 'required', 'string', 'min:2', 'max:60',
                'regex:' . GodName::PATTERN,
                function (string $attribute, mixed $value, \Closure $fail) use ($god): void {
                    if (!is_string($value)) return;
                    if (God::nameIsTaken($value, $god?->id)) {
                        $fail('Бог с таким именем уже существует в пантеоне.');
                    }
                },
            ],
            'type' => ['required', 'string', Rule::in(GodType::OPTIONS)],
            'published_at' => ['required', 'date_format:Y-m-d', 'after_or_equal:2000-01-01', 'before_or_equal:today'],
            'short_description' => ['bail', 'required', 'string', 'min:20', 'max:300', $textHasLetters],
            'description' => ['bail', 'required', 'string', 'min:50', 'max:5000', $textHasLetters],
            'fact' => ['bail', 'required', 'string', 'min:12', 'max:500', $textHasLetters],
            'image' => [Rule::requiredIf($creating), 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], $this->messages(), $this->attributes());

        // image — UploadedFile, а не поле модели; ключ настраивается только сервером.
        unset($data['image']);
        return $data;
    }

    // Преобразует конфликт уникальности в ошибку поля при одновременной записи.
    private function rethrowNameConflict(QueryException $e): never
    {
        $message = mb_strtolower($e->getMessage(), 'UTF-8');
        if (str_contains($message, 'gods.name_key') || str_contains($message, 'gods_active_name_unique')) {
            throw ValidationException::withMessages([
                'name' => 'Бог с таким именем уже существует в пантеоне.',
            ]);
        }
        throw $e;
    }

    private function messages(): array
    {
        return [
            'required' => 'Заполните поле «:attribute».',
            'string' => 'Поле «:attribute» должно содержать текст.',
            'min.string' => 'Поле «:attribute»: минимум :min символов.',
            'max.string' => 'Поле «:attribute»: максимум :max символов.',
            'name.regex' => 'Имя: только русские или английские буквы, пробелы и дефисы (без цифр и знаков). Не смешивайте алфавиты.',
            'type.in' => 'Выберите сферу покровительства из списка.',
            'published_at.date_format' => 'Укажите корректную дату публикации.',
            'published_at.after_or_equal' => 'Дата публикации не может быть раньше 01.01.2000.',
            'published_at.before_or_equal' => 'Дата публикации не может быть в будущем.',
            'image.image' => 'Файл должен быть изображением.',
            'image.mimes' => 'Допустимые форматы изображения: JPG, PNG и WebP.',
            'image.max' => 'Изображение должно быть не больше 5 МБ.',
            'image.uploaded' => 'Не удалось загрузить файл. Проверьте размер и настройки PHP.',
        ];
    }

    private function attributes(): array
    {
        return [
            'name' => 'Имя бога',
            'type' => 'Сфера покровительства',
            'published_at' => 'Дата публикации',
            'short_description' => 'Краткое описание',
            'description' => 'История и покровительство',
            'fact' => 'Интересный факт',
            'image' => 'Изображение',
        ];
    }
}
