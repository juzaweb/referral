<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Referral\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawMethodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
			'name' => ['required'],
            'description' => ['nullable', 'string'],
            'min_amount' => ['required', 'numeric', 'min:0'],
            'fields' => ['required', 'array'],
            'fields.*.label' => ['required', 'string'],
            'fields.*.name' => ['required', 'string', 'distinct', 'regex:/^[a-zA-Z_][a-zA-Z0-9_]*$/'],
		];
    }
}
