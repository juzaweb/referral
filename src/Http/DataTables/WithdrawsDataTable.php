<?php

namespace Juzaweb\Modules\Referral\Http\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Juzaweb\Modules\Withdraw\Models\Withdraw;
use Yajra\DataTables\EloquentDataTable;

class WithdrawsDataTable extends DataTable
{
    protected string $actionUrl = 'withdraws/bulk';

    public function query(Withdraw $model): Builder
    {
        return $model->newQuery()->with(['withdrawable']);
    }

    public function getColumns(): array
    {
        return [
			Column::checkbox(),
			Column::id(),
			Column::actions(),
			Column::computed('user', __('referral::translation.user')),
			Column::make('method'),
			Column::make('amount'),
			Column::computed('note'),
			Column::make('status'),
			Column::createdAt(),
		];
    }

    public function actions(Model $model): array
    {
        return [

        ];
    }

    public function bulkActions(): array
    {
        return [
            //BulkAction::delete()->can('withdraws.delete'),
        ];
    }

    public function renderColumns(EloquentDataTable $builder): EloquentDataTable
    {
        return parent::renderColumns($builder)
            ->editColumn('user', function (Withdraw $model) {
                return $model->withdrawable?->name;
            })
            ->editColumn('note', function (Withdraw $model) {
                return nl2br(e($model->note));
            });
    }
}
