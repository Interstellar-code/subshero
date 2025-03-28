{{-- Subscription --}}
@if ($val->type == 1)
    {{ table('subscription.cycle_ly', $val->billing_cycle) }}
@else
    {{ table('subscription.type', $val->type) }}
@endif

@if ($val->payment_date)
    <br>
    <small>{{ date('d M Y', strtotime($val->payment_date)) }}</small>
@endif
