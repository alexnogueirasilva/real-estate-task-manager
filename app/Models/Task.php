<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property int $assigned_to
 * @property int $building_id
 *
 */
class Task extends Model
{
    use HasFactory;
}
