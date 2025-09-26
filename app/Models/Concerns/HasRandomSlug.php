<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait HasRandomSlug
{
    protected static string $slugColumn = 'slug';

    public static function bootHasRandomSlug()
    {
        static::creating(function (Model $model) {
            $slugColumn = static::$slugColumn;

            if (empty($model->{$slugColumn})) {
                $model->{$slugColumn} = static::makeUniqueSlug($model);
            }
        });
    }

    protected static function makeUniqueSlug(Model $model): string
    {
        $slugColumn = static::$slugColumn;
        do {
            $slug = Str::random(8);
        } while (
            $model->newQuery()
                ->where($slugColumn, $slug)
                ->exists()
        );
        return $slug;
    }
}
