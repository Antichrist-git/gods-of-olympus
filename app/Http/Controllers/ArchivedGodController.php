<?php

namespace App\Http\Controllers;

use App\Models\God;
use App\Support\GodImage;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ArchivedGodController extends Controller
{
    public function index(): View
    {
        return view('gods.archive', [
            'gods' => God::onlyTrashed()->with('user')->when(request()->filled('q'), fn ($query) => $query->where('name_key', 'like', '%'.\App\Support\GodName::key(request()->string('q')->toString()).'%'))
                ->orderByDesc('deleted_at')->orderByDesc('id')->paginate(12)->withQueryString(),
        ]);
    }

    public function restore(int $id): RedirectResponse
    {
        try {
            DB::transaction(function () use ($id) {
                God::onlyTrashed()->findOrFail($id)->restore();
            });
        } catch (UniqueConstraintViolationException $e) {
            if (!str_contains($e->getMessage(), 'gods.name_key')) {
                throw $e;
            }
            return back()->withErrors(['restore' => 'Это имя уже занято. Переименуйте активную карточку и повторите восстановление.']);
        }
        return back()->with('success', 'Карточка восстановлена.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $path = DB::transaction(function () use ($id) {
            $god = God::onlyTrashed()->findOrFail($id);
            $god->forceDelete();
            return $god->image_path;
        });
        if ($path && !God::withTrashed()->where('image_path', $path)->exists()) {
            GodImage::removeUploaded($path);
        }
        return back()->with('success', 'Карточка удалена окончательно.');
    }
}
