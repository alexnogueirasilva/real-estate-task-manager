<?php

namespace App\Http\Resources\Api\Building;

use App\Models\{Task, User};
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $comment
 * @property User $users
 * @property Task $task
 */
class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'comment' => $this->comment,
            'user'    => $this->users->name,
            'task'    => $this->task,
        ];
    }
}
