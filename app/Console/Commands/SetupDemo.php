<?php

namespace App\Console\Commands;

use App\Events\FriendAdded;
use App\Models\Comment;
use App\Models\God;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SetupDemo extends Command
{
    protected $signature = 'olympus:demo';
    protected $description = 'Подготовить пустую базу для показа WT4 и WT5';

    public function handle(): int
    {
        $password = Str::random(16);
        $created = DB::transaction(function () use ($password) {
            if (User::exists() || God::withTrashed()->exists()) {
                return false;
            }

            $users = [];
            foreach (['anna' => 'Анна', 'maxim' => 'Максим', 'olga' => 'Ольга'] as $username => $name) {
                $user = new User(['name' => $name, 'username' => $username,
                    'email' => $username.'@example.test', 'password' => $password]);
                $user->is_admin = $username === 'maxim';
                $user->save();
                $users[] = $user;
            }

            [$anna, $maxim, $olga] = $users;
            app(DatabaseSeeder::class)->run();
            $gods = God::orderBy('id')->get();
            foreach ($gods as $index => $god) {
                DB::table('gods')->where('id', $god->id)->update([
                    'user_id' => $users[$index % count($users)]->id,
                    'created_at' => now()->subDays(count($gods) - $index),
                ]);
            }

            $anna->friends()->attach($maxim->id);
            event(new FriendAdded($anna->id, $maxim->id));

            $card = $maxim->gods()->latest('created_at')->firstOrFail();
            foreach ([[$maxim, 'Добавил интересный факт в описание.'], [$olga, 'Спасибо за подборку!']] as [$author, $body]) {
                $comment = new Comment();
                $comment->body = $body;
                $comment->user()->associate($author);
                $card->comments()->save($comment);
            }
            return true;
        });

        if (!$created) {
            $this->warn('В базе уже есть аккаунты или карточки. Данные сохранены без изменений.');
            return self::SUCCESS;
        }
        $this->info('Анна: anna@example.test · Максим (администратор): maxim@example.test · Ольга: olga@example.test');
        $this->line('Пароль для трёх демонстрационных аккаунтов: '.$password);
        return self::SUCCESS;
    }
}
