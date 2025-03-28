@switch($val->status)
    @case(0)
        <span class="badge badge-info">Pending</span>
    @break

    @case(1)
        <span class="badge badge-success">Sent</span>
    @break

    @case(2)
        <span class="badge badge-danger">Failed</span>
    @break

    @default
        <span class="badge badge-dark">Unknown</span>
@endswitch
