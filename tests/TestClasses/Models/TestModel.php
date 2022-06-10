<?php

namespace Baro\PipelineQueryCollection\Tests\TestClasses\Models;

use Baro\PipelineQueryCollection\BitwiseFilter;
use Baro\PipelineQueryCollection\BooleanFilter;
use Baro\PipelineQueryCollection\Concerns\Filterable;
use Baro\PipelineQueryCollection\DateFromFilter;
use Baro\PipelineQueryCollection\DateToFilter;
use Baro\PipelineQueryCollection\ExactFilter;
use Baro\PipelineQueryCollection\RelationFilter;
use Baro\PipelineQueryCollection\RelativeFilter;
use Baro\PipelineQueryCollection\ScopeFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasFactory;
    use Filterable;

    protected $guarded = [];

    protected function getFilters()
    {
        return [
            new BitwiseFilter('type_flag'),
            new BooleanFilter('is_visible'),
            new DateFromFilter('created_at'),
            new DateToFilter('created_at'),
            new ExactFilter('updated_at'),
            new RelationFilter('belongs_to_related_models', 'id'),
            new RelationFilter('belongs_to_many_related_models', 'id'),
            new RelativeFilter('name'),
            new ScopeFilter('search'),
        ];
    }

    public function belongsToRelatedModels()
    {
        return $this->belongsTo(RelatedModel::class, 'related_model_id');
    }

    public function belongsToManyRelatedModels()
    {
        return $this->belongsToMany(RelatedModel::class, 'pivot_table');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where(
            fn (Builder $query) => $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('id', $search)
        );
    }
}