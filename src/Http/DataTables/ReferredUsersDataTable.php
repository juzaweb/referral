<?php

namespace Juzaweb\Modules\Referral\Http\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Juzaweb\Modules\Admin\Models\User;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Juzaweb\Modules\Referral\Models\Referral;
use Yajra\DataTables\EloquentDataTable;

class ReferredUsersDataTable extends DataTable
{
    public function query(Referral $model): Builder
    {
        return $model->newQuery()
            ->with(['referred'])
            ->where('referrer_id', auth()->id())
            ->where('referred_type', User::class);
    }

    public function getColumns(): array
    {
        return [
            Column::id(),
            Column::computed('name')->title(__('referral::translation.name')),
            Column::createdAt(),
        ];
    }

    public function renderColumns(EloquentDataTable $builder): EloquentDataTable
    {
        return parent::renderColumns($builder)->editColumn(
            'name',
            function (Referral $referral) {
                return $referral->referred->name ?? '';
            }
        );
    }
}
