<?php

namespace App\Services;

use App\Models\Round;
use App\Traits\crud;

class RoundService extends BaseService
{
    use crud;

    public function model()
    {
        return Round::class;
    }

    public function getActiveRoundByUserId($userId, $request)
    {
        $round = $this->query()->where([
            'user_id' => $userId,
            'play_status' => 'not_played',
        ])->orderBy('created_at', 'DESC')->first();

        return $round;

    }

    public function getRoundsByUserId($userId, \Illuminate\Http\Request $request)
    {
        $round = $this->query()->where([
            'user_id' => $userId,
        ])->orderBy('created_at', 'DESC')->get();

        return $round;
    }

}
