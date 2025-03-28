@if ($val->type == 3)
    {{-- For lifetime --}}
    @if (!empty($val->refund_date))
        {{ date('d M Y', strtotime($val->refund_date)) }}
    @endif
@else
    @if ($val->next_payment_date)
        {{ date('d M Y', strtotime($val->next_payment_date)) }}
    @else
        {{ date('d M Y', strtotime($val->payment_date)) }}
    @endif

    @if ($val->alert_type == 1)
        🔔
    @endif
@endif
