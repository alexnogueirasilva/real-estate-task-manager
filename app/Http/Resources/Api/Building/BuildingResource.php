<?php

namespace App\Http\Resources\Api\Building;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property Collection|LengthAwarePaginator $tasks
 */
class BuildingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tasks = $this->whenLoaded('tasks');

        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'address' => $this->address,
            'tasks'   => $tasks instanceof LengthAwarePaginator ? [
                'data'       => TaskResource::collection($tasks->items()), // Os itens paginados
                'pagination' => [
                    'total'         => $tasks->total(),
                    'per_page'      => $tasks->perPage(),
                    'current_page'  => $tasks->currentPage(),
                    'last_page'     => $tasks->lastPage(),
                    'next_page_url' => $tasks->nextPageUrl(),
                    'prev_page_url' => $tasks->previousPageUrl(),
                ],
            ] : TaskResource::collection($tasks),
        ];
    }
}
