<?php

namespace App\Http\Resources\Api\Building;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property User $user
 * @property Carbon $created_at
 * @property Collection $comments
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            'assigned_to' => $this->user->name,
            'created_at'  => $this->created_at->format('Y-m-d'),
            'comments'    => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
