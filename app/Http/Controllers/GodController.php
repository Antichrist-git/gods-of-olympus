<?php

namespace App\Http\Controllers;

use App\Models\God;
use App\Services\GodWriter;
use App\Support\GodName;
use App\Support\GodType;
use Illuminate\Http\Request;

class GodController extends Controller
{
    public function index()
    {
        $gods = God::orderBy('id')->get();
        return view('gods.index', compact('gods'));
    }

    // AJAX-подсказка: имя занято или свободно; окончательная проверка остаётся в validated().
    public function checkName(Request $request)
    {
        $raw = $request->query('name', '');
        if (!is_string($raw)) {
            return response()->json(['available' => false, 'message' => 'Укажите имя бога.']);
        }

        $name = GodName::normalize($raw);
        if (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 60 ||
            !preg_match(GodName::PATTERN, $name)) {
            return response()->json(['available' => false, 'message' => 'Введите 2–60 букв без цифр и специальных символов.']);
        }

        $excludeId = $request->query('exclude_id');
        $currentId = is_string($excludeId) && ctype_digit($excludeId) ? (int) $excludeId : null;
        $occupied = God::nameIsTaken($name, $currentId);

        return response()->json([
            'available' => !$occupied,
            'message' => $occupied ? 'Бог с таким именем уже существует в пантеоне.' : 'Имя свободно.',
        ]);
    }

    public function create()
    {
        return view('gods.form', ['types' => GodType::OPTIONS]);
    }

    public function store(Request $request, GodWriter $writer)
    {
        $writer->create($request);

        return redirect()->route('gods.index')->with('success', 'Бог добавлен.');
    }

    // Возвращает страницу для обычного запроса и фрагмент для быстрого просмотра.
    public function show(Request $request, God $god)
    {
        if ($request->ajax()) {
            return view('gods.partials.details', compact('god'));
        }
        $comments = $god->comments()->with('user')->latest('id')->simplePaginate(20)->fragment('comments');
        // Проверяем дружбу одним запросом для авторов текущей страницы.
        $friendIds = $request->user()?->friends()
            ->whereIn('users.id', $comments->getCollection()->pluck('user_id'))
            ->pluck('users.id')->flip() ?? collect();
        return view('gods.show', compact('god', 'comments', 'friendIds'));
    }

    public function edit(God $god)
    {
        return view('gods.form', ['god' => $god, 'types' => GodType::OPTIONS]);
    }

    // Заменяет старое изображение после успешного сохранения карточки.
    public function update(Request $request, God $god, GodWriter $writer)
    {
        $writer->update($request, $god);

        return redirect()->route('gods.index')->with('success', 'Изменения сохранены.');
    }

    public function destroy(God $god)
    {
        $god->delete(); // Soft Deletes: проставляем deleted_at, не удаляя запись/фото.
        return redirect()->route('gods.index')->with('success', 'Бог удалён.');
    }

}
