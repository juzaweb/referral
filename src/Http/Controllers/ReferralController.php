<?php

namespace Juzaweb\Referral\Http\Controllers;

use Juzaweb\CMS\Http\Controllers\BackendController;

class ReferralController extends BackendController
{
    public function index()
    {
        //

        return view(
            'referral::index',
            [
                'title' => 'Title Page',
            ]
        );
    }
}
