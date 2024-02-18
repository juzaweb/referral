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
                'label' => __('Referral'),
                'menu' => [
                    'parent' => 'referral',
                    'position' => 99,
                ]
            ]
        );

        $this->hookAction->addSettingForm(
            'referral',
            [
                'name' => __('Settings'),
                'page' => 'referral',
            ]
        );

        $this->hookAction->registerConfig(
            [
                'referral_enable' => [
                    'type' => 'select',
                    'label' => __('Enable Referral'),
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

        if (plugin_enabled('juzaweb/user-credit')) {
            $this->addConfigCredits();
        }
    }

    public function addConfigCredits(): void
    {
        $this->hookAction->registerConfig(
            [
                'referral_credit_on_registed' => [
                    'type' => 'select',
                    'label' => 'Earn credit when user register successfully',
                    'form' => 'referral',
                    'data' => [
                        'options' => [
                            0 => trans('cms::app.disabled'),
                            1 => trans('cms::app.enable'),
                        ],
                    ]
                ],
                'referral_credit_on_registed_number' => [
                    'type' => 'text',
                    'label' => 'Credits number',
                    'form' => 'referral',
                ],
            ]
        );
    }
}
