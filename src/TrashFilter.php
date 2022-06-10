<?php

namespace Baro\PipelineQueryCollection;

use Baro\PipelineQueryCollection\Enums\TrashOptionEnum;
use Illuminate\Database\Eloquent\Builder;

class TrashFilter extends BaseFilter
{
    public function __construct($field = 'trashed')
    {
        parent::__construct();
        $this->field = $field;
    }

    protected function apply(Builder $query): Builder
    {
        $option = TrashOptionEnum::tryFrom($this->getSearchValue()[0]);
        match ($option) {
            TrashOptionEnum::ONLY => $query->onlyTrashed(),
            TrashOptionEnum::WITH => $query->withTrashed(),
            default => $query,
        };
        return $query;
    }
}
