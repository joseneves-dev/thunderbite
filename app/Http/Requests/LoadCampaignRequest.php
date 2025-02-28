<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoadCampaignRequest extends FormRequest
{
    public function rules()
    {
        return [
            'a' => 'nullable|string', // Account
            'segment' => 'nullable|string',
        ];
    }
}