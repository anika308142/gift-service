<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class RoundFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function round($roundId): RoundFilter
    {
        return $this->where('id', $roundId);
    }

    public function user($userId): RoundFilter
    {
        return $this->where('user_id', $userId);
    }

    public function gif($gift_status): RoundFilter
    {
        return $this->where('gift_status', $gift_status);

    }
}
