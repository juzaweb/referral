@extends('core::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="float-right">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="referral-system-toggle"
                        {{ setting('enable_referral_system', false) ? 'checked' : '' }}>
                    <label class="custom-control-label"
                        for="referral-system-toggle">{{ __('referral::translation.enable_referral_system') }}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        {{-- <div class="col-md-12">
            <x-core::datatables.filters>
                <div class="col-md-3 jw-datatable_filters">

                </div>
            </x-core::datatables.filters>
        </div> --}}

        <div class="col-md-12 mt-2">
            <x-card title="{{ __('referral::translation.referrals') }}">
                {{ $dataTable->table() }}
            </x-card>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts(null, ['nonce' => csp_script_nonce()]) }}

    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        $('#referral-system-toggle').on('change', function() {
            let enabled = $(this).is(':checked') ? 1 : 0;
            $.ajax({
                url: '{{ route('referrals.toggle-system') }}',
                type: 'POST',
                data: {
                    enabled: enabled
                },
                success: function(response) {
                    show_notify(response);
                },
                error: function(xhr) {
                    show_notify(xhr);
                }
            });
        });
    </script>
@endsection
