<?php

namespace App\Console\Commands;

use App\Models\God;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignGodOwners extends Command
{
    protected $signature = 'gods:assign-owners {emails* : Адреса зарегистрированных владельцев}';
    protected $description = 'Распределить старые карточки без владельца между указанными аккаунтами';

    public function handle(): int
    {
        $owners = [];
        foreach (array_unique($this->argument('emails')) as $email) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("Аккаунт $email не найден.");
                return self::FAILURE;
            }
            $owners[] = $user->id;
        }

        // Прежние назначения сохраняются при повторном запуске.
        $count = DB::transaction(function () use ($owners) {
            $ids = God::withTrashed()->whereNull('user_id')->orderBy('id')->pluck('id');
            $assigned = 0;
            foreach ($ids as $index => $id) {
                $assigned += DB::table('gods')->where('id', $id)->whereNull('user_id')
                    ->update(['user_id' => $owners[$index % count($owners)]]);
            }
            return $assigned;
        });
        $this->info("Назначено владельцев для карточек: $count.");
        return self::SUCCESS;
    }
}
