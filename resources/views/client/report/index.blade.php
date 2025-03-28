@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('head')
@endsection

@section('content')
    <style>
        .report_page_elements {
            display: block;
        }
    </style>

    <div class="main-card mb-3">

        <div id="report_chart_container" class="report_chart_container">
            @include('client/report/subscription')
        </div>

    </div>
@endsection
