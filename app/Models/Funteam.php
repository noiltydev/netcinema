<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\FunteamBuilder;
use Database\Factories\FunteamFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug'])]
class Funteam extends Model
{
    /** @use HasFactory<FunteamFactory> */
    use HasFactory;

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): FunteamBuilder
    {
        /** @var FunteamBuilder */
        return parent::query();
    }
}
