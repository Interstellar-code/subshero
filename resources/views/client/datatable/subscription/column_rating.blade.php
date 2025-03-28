<span class="text-nowrap">
    @for($i = 0; $i <= 4; $i++)
        @if ($rating/2 > $i && $rating/2 < $i+1)
            <span class="d-inline-block position-relative">
                <span>
                    <i class="fa fa-star" style="color:#ddd;font-szie:11px;"></i>
                </span>
                <span style="    position: absolute;
                left: 0;
                display: inline-block;
                width: 8.5px;
                overflow: hidden;">
                    <i class="fa fa-star" style="color:#ffc700;font-szie:11px;"></i>
                </span>
            </span>
        @elseif (floor($rating/2) >= $i+1)
            <i class="fa fa-star" style="color:#ffc700;font-szie:11px;"></i>
        @elseif (floor($rating/2) <= $i+1)
            <i class="fa fa-star" style="color:#ddd;font-szie:11px;"></i>
        @endif
    @endfor
</span>