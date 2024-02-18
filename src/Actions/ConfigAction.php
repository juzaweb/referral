<?php

namespace Juzaweb\Referral\Actions;

use Juzaweb\CMS\Abstracts\Action;

class ConfigAction extends Action
{
    public function handle(): void
    {
        $this->addAction(Action::INIT_ACTION, [$this, 'addConfigs']);
    }

    public function addConfigs(): void
    {
        $this->registerAdminPage(
            'referral',
            [
                'title' => trans('referral::content.referral'),
                'menu' => [
                    'position' => 30,
                ]
            ]
        );

        $this->hookAction->registerSettingPage(
            'referral',
            [
                'label' => trans('cms::app.referral'),
                'menu' => [
                    'parent' => 'referral',
                    'position' => 99,
                ]
            ]
        );

        $this->hookAction->addSettingForm(
            'referral',
            [
                'name' => 'Settings',
                'page' => 'referral'
            ]
        );

        $this->hookAction->registerConfig(
            [
                'referral_enable' => [
                    'type' => 'select',
                    'label' => 'Enable Referral',
                    'form' => 'referral',
                    'data' => [
                        'options' => [
                            0 => trans('cms::app.disabled'),
                            1 => trans('cms::app.enable'),
                        ],
                    ]
                ],
            ]
        );
    }
}
