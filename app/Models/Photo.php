<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Cache;

class Photo extends Model
{
    use HasFactory, HasSlug;

    protected $perPage = 9;

    //met à jour le cache lors de la création d'une nouvelle photo
    public static function boot()
    {
        parent::boot();
        static::created(function() {
            Cache::flush();
        });

        parent::boot();
        static::updated(function() {
            Cache::flush();
        });

        parent::boot();
        static::deleted(function() {
            Cache::flush();
        });
    }

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', true);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnCreate();
    }

    public function album() {
        return $this->belongsTo(Album::class);
    }

    public function sources() {
        return $this->hasMany(Source::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

}
