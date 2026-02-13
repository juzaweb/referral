<?php

namespace Juzaweb\Modules\Referral\Http\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\DataTables\Action;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Juzaweb\Modules\Referral\Models\Referral;
use Yajra\DataTables\EloquentDataTable;

class ReferralsDataTable extends DataTable
{
    protected string $actionUrl = 'referrals/bulk';

    public function query(Referral $model): Builder
    {
        return $model->newQuery()->with(['referrer', 'referred']);
    }

    public function renderColumns(EloquentDataTable $builder): EloquentDataTable
    {
        $builder->editColumn(
            'referrer',
            function (Referral $model) {
                if ($model->referrer) {
                    return $model->referrer->name . ' (' . $model->referrer->email . ')';
                }
                return 'N/A';
            }
        );

        $builder->editColumn(
            'referred',
            function (Referral $model) {
                if ($model->referred) {
                    return $model->referred->name . ' (' . $model->referred->email . ')';
                }
                return 'N/A';
            }
        );

        return parent::renderColumns($builder);
    }

    public function getColumns(): array
    {
        return [
			Column::checkbox(),
			Column::id(),
			Column::actions(),
			Column::make('referrer')->title(__('referral::translation.referrer')),
			Column::make('referred')->title(__('referral::translation.referred')),
			Column::createdAt(),
		];
    }

    public function actions(Model $model): array
    {
        return [
            Action::delete()->can('referrals.delete'),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete()->can('referrals.delete'),
        ];
    }
}
