<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoundGiftStatusUpdateRequest;
use App\Http\Requests\RoundResultUpdateRequest;
use App\Http\Resources\RoundResource;
use App\Models\Round;
use App\Services\RoundService;
use Illuminate\Http\Request;

class RoundController extends Controller
{
    public function __construct(protected RoundService $roundService)
    {
    }

    public function getActiveRoundByUserId($userId, Request $request): RoundResource|array
    {
        $round = $this->roundService->getActiveRoundByUserId($userId, $request);

        if (!$round) {
            return [
                "data" => null,
            ];
        }

        return new RoundResource($round);
    }

    public function getRoundsByUserId($userId, Request $request)
    {
        $rounds = $this->roundService->getRoundsByUserId($userId, $request);

        return RoundResource::collection($rounds);

    }

    public function updateRoundResultByRoundId($roundId, RoundResultUpdateRequest $roundUpdateRequest): RoundResource
    {
        $round = $this->roundService->details($roundId, $roundUpdateRequest);
        if ($round->play_status == "played") {
            return new RoundResource($round);
        } else {
            if ($roundUpdateRequest->result == 'win') {
                $roundUpdateRequest->offsetSet('gift_status', 'pending');
                $roundUpdateRequest->offsetSet('play_status', 'played');
            } elseif ($roundUpdateRequest->result == 'lose') {
                $roundUpdateRequest->offsetSet('play_status', 'played');
            }
        }

        $round = $this->roundService->update($roundId, $roundUpdateRequest);
        return new RoundResource($round);
    }

    public function getRounds(Request $request)
    {
        $rounds = $this->roundService->all($request);

        return RoundResource::collection($rounds);

    }

    public function updateRoundGiftStatusByRoundId($roundId, RoundGiftStatusUpdateRequest $roundGiftStatusUpdateRequest): RoundResource
    {
        $round = $this->roundService->details($roundId, $roundGiftStatusUpdateRequest);
        if ($round->gift_status == "pending") {
            $round = $this->roundService->update($roundId, $roundGiftStatusUpdateRequest);

        }

        return new RoundResource($round);

    }

}
