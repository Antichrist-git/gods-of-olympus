<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'user_id' => $this->user_id,
            'is_friend' => (bool) $this->is_friend,
            'created_at' => $this->created_at?->toAtomString(),
            'god' => [
                'id' => $this->god->id,
                'name' => $this->god->name,
                'type' => $this->god->type,
            ],
        ];
    }
}
