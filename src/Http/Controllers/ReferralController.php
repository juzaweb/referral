<?php

namespace Juzaweb\Modules\Referral\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Referral\Http\DataTables\ReferralsDataTable;
use Juzaweb\Modules\Referral\Http\Requests\ReferralActionsRequest;
use Juzaweb\Modules\Referral\Models\Referral;

class ReferralController extends AdminController
{
    public function index(ReferralsDataTable $dataTable)
    {
        Breadcrumb::add(__('referral::translation.referrals'));

        return $dataTable->render(
            'referral::referral.index'
        );
    }

    public function bulk(ReferralActionsRequest $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        $models = Referral::whereIn('id', $ids)->get();

        foreach ($models as $model) {
            if ($action === 'delete') {
                $model->delete();
            }
        }

        return $this->success([
            'message' => __('referral::translation.bulk_action_performed_successfully'),
        ]);
    }

    public function toggleSystem(Request $request): JsonResponse
    {
        $enabled = (int) $request->post('enabled');

        setting()->set('enable_referral_system', $enabled);

        return $this->success([
            'message' => __('referral::translation.referral_system_setting_updated_successfully'),
        ]);
    }
}
