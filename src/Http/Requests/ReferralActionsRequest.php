<?php

namespace Juzaweb\Modules\Referral\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Juzaweb\Modules\Core\Rules\AllExist;

class ReferralActionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'action' => ['required'],
            'ids' => ['required', 'array', 'min:1', new AllExist('referrals', 'id')],
        ];
    }
}
