<?php

namespace Juzaweb\Modules\Referral\Http\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\DataTables\Action;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Juzaweb\Modules\Withdraw\Models\WithdrawMethod;

class WithdrawMethodsDataTable extends DataTable
{
    protected string $actionUrl = 'withdraw-methods/bulk';

    public function query(WithdrawMethod $model): Builder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
			Column::checkbox(),
			Column::id(),
			Column::actions(),
			Column::editLink('name', admin_url('withdraw-methods/{id}/edit'), __('referral::translation.name')),
			Column::createdAt(),
		];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("withdraw-methods/{$model->id}/edit"))->can('withdraw-methods.edit'),
            Action::delete()->can('withdraw-methods.delete'),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete()->can('withdraw-methods.delete'),
        ];
    }
}
