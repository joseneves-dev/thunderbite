<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class FlipTileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'gameId' => 'required|integer',
            'tileIndex' => 'required|integer',
        ];
    }
}