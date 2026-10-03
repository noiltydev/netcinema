<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FunteamFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug'])]
class Funteam extends Model
{
    /** @use HasFactory<FunteamFactory> */
    use HasFactory;
}
