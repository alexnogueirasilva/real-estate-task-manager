<?php

namespace App\Http\Resources\Api\Building;

use App\Models\{Building, User};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $description
 * @property string $status
 * @property User $user
 * @property Building $building
 * @property Collection $comments
 */
class BuildingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'description' => $this->description,
            'status'      => $this->status,
            'assigned_to' => $this->user,
            'building'    => $this->building,
            'comments'    => CommentResource::collection($this->comments),
        ];
    }
}
