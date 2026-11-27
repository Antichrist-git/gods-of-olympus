<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\God;
use App\Models\User;
use App\Support\GodType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExpandOlympusDemo extends Command
{
    protected $signature = 'olympus:expand-demo';
    protected $description = 'Добавить тематические публикации и обсуждения в демонстрационную коллекцию';

    public function handle(): int
    {
        DB::transaction(function () {
            $authors = [];
            foreach (['demo-vera' => 'Вера', 'demo-lev' => 'Лев', 'demo-sofia' => 'София',
                'demo-ivan' => 'Иван', 'demo-mira' => 'Мира'] as $login => $name) {
                $email = $login.'@example.test';
                $user = User::firstOrNew(['email' => $email]);
                if (!$user->exists) {
                    $user->fill(['username' => $login, 'name' => $name, 'password' => Str::random(32)]);
                    $user->created_at = now()->subDays(55 + count($authors))->subHours(count($authors));
                    $user->save();
                }
                $authors[] = $user;
            }
            $maxim = User::where('email', 'maxim@example.test')->first();
            if ($maxim) {
                foreach (array_slice($authors, 0, 3) as $friend) {
                    $maxim->friends()->syncWithoutDetaching([$friend->id]);
                    $friend->friends()->syncWithoutDetaching([$maxim->id]);
                }
            }

            $stories = [
                ['Гера', 15, 'hera', 'Царица олимпийцев и покровительница брака.', 'В искусстве Геру часто изображают с павлином.', 'Теперь понятно, почему рядом с Герой павлин.'],
                ['Арес', 11, 'ares', 'Олимпийский бог войны, спутник сражающихся воинов.', 'Ареса узнают по шлему, щиту и копью.', 'Интересно сравнить этот образ с Афиной.'],
                ['Гермес', 5, 'hermes', 'Вестник олимпийцев и покровитель путников.', 'Крылатые сандалии помогают Гермесу пересекать огромные расстояния.', 'По сандалиям сразу узнала вестника.'],
                ['Деметра', 9, 'demeter', 'Покровительница земледелия и созревающего хлеба.', 'История Деметры и Персефоны объясняла смену времён года.', 'Люблю миф о возвращении Персефоны.'],
                ['Артемида', 8, 'artemis', 'Богиня охоты и хранительница дикой природы.', 'Лук и олень сопровождают Артемиду в античном искусстве.', 'Олень хорошо дополняет эту сцену.'],
                ['Афродита', 6, 'aphrodite', 'Богиня любви и красоты, связанная с островом Кипр.', 'Одна из версий мифа связывает рождение Афродиты с морской пеной.', 'Морской фон здесь очень кстати.'],
                ['Гефест', 7, 'hephaestus', 'Божественный кузнец и мастер олимпийцев.', 'В Илиаде Гефест выковал щит для Ахилла.', 'Хотелось бы увидеть рисунки на щите Ахилла.'],
                ['Дионис', 16, 'dionysus', 'Покровитель виноградной лозы и театральных праздников.', 'Тирс Диониса часто изображали обвитым плющом.', 'Раньше я замечал только виноград.'],
                ['Гестия', 10, 'hestia', 'Хранительница домашнего и общественного очага.', 'Очаг объединял домочадцев во время общих обрядов.', 'Спокойный образ среди олимпийцев.'],
                ['Гелиос', 17, 'helios', 'Солнечное божество, совершающее путь по небу.', 'Гелиос объезжал небо на сияющей колеснице.', 'Теперь отличаю Гелиоса от Аполлона.'],
                ['Ника', 12, 'nike', 'Олицетворение победы в греческой мифологии.', 'На изображениях Ника вручает венок победителю.', 'Узнала Нику по крыльям и венку.'],
                ['Гипнос', 13, 'hypnos', 'Божество сна и брат Танатоса.', 'Маки и крылья у висков помогают узнать Гипноса.', 'Маки на портрете приобрели смысл.'],
            ];
            foreach ($stories as $i => [$name, $type, $slug, $intro, $fact, $remark]) {
                if (God::withTrashed()->where('name_key', \App\Support\GodName::key($name))->exists()) continue;
                $published = now()->subDays(30 - $i * 2)->startOfDay()->addHours(8 + $i % 13)->addMinutes(($i * 17 + 9) % 60);
                $god = $authors[$i % 5]->gods()->make([
                    'name' => $name, 'type' => $type === null ? 'Другое божество' : GodType::OPTIONS[$type],
                    'short_description' => $intro, 'description' => $intro.' '.$fact, 'fact' => $fact,
                    'published_at' => $published->toDateString(), 'image_path' => 'gods/'.$slug.'.jpg',
                ]);
                $god->created_at = $god->updated_at = $published;
                $god->save();
                $notes = [$remark, 'Хочу ещё почитать об этом божестве.', 'Запомнил эту деталь: '.$fact];
                $notes[] = ['Какие ещё мифы связаны с этим божеством?', 'Хорошая деталь для сравнения с римской традицией.', 'Интересно было бы увидеть античное изображение.'][$i % 3];
                foreach (array_slice($notes, 0, 3 + ($i % 4 === 0 ? 1 : 0)) as $j => $body) {
                    $comment = new Comment();
                    $comment->body = $body;
                    $comment->user()->associate($authors[($i + $j + 1) % 5]);
                    $comment->created_at = $comment->updated_at = $published->copy()->addMinutes(31 + $i * 13 + $j * 233);
                    $god->comments()->save($comment);
                }
            }
        });
        $this->info('Демонстрационные коллекции готовы.');
        return self::SUCCESS;
    }
}
