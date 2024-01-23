<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id ?? "",
            'payment_id'=> $this->payment_id ?? "",
            'result' => $this->result ?? "",
            'play_status' => $this->play_status ?? "",
            'gift_status' => $this->gift_status ?? "",

        ];
    }
}
