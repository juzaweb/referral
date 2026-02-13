<?php

namespace Juzaweb\Modules\Referral\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Juzaweb\Modules\Core\Rules\AllExist;

class WithdrawMethodActionsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'action' => ['required'],
            'ids' => ['required', 'array', 'min:1', new AllExist('withdraw_methods', 'id')],
        ];
    }
}
