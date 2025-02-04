<?php

namespace App\Models\Blog;

use App\Models\Scientific\ScientificDepartment;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia, HasTags, HasUuids;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'blog_posts';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'blog_author_id',
        'name',
        'slug',
        'description',
        'short_description',
        'published_at',
        'is_featured',
        'scientific_department_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'date',
        'is_featured' => 'boolean',
        'name' => 'array',
        'description' => 'array',
        'short_description' => 'array',
    ];

    /** @return BelongsTo<User,self> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blog_author_id');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('post-banners')?->first()?->getUrl() ?? $this->getMedia('post-banners')?->first()?->getUrl('thumb') ?? null;
    }

    public function registerMediaConversions(Media|null $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function scientific_department(): BelongsTo
    {
        return $this->belongsTo(ScientificDepartment::class);
    }
}
