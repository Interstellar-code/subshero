@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('head')
@endsection

@section('content')
    <form class="row" id="frm_notification_edit" method="POST">
        @csrf

        <div class="col-md-12 col-xl-12">
            <div class="position-relative form-group">
                <label for="title" class="">@lang('Title')</label>
                <input name="tile" id="notification_edit_title" placeholder="@lang('Title')" type="text" class="form-control" value="{{ $data->title }}" readonly>
            </div>
            <div class="position-relative form-group">
                <label for="message" class="">@lang('Message')</label>
                <textarea name="message" id="notification_edit_message" placeholder="@lang('Message')" type="text" class="form-control" style="overflow-y: hidden;" readonly>{{ $data->message }}</textarea>
            </div>
            <div class="position-relative form-group">
                <label for="Date" class="">@lang('Date')</label>
                <input name="date" id="notification_edit_date" placeholder="@lang('Date')" type="text" placeholder="yyyy-mm-dd" maxlength="{{ len()->subscriptions->payment_date }}" class="form-control" value="{{ $data->event_type_scdate }}" data-toggle="datepicker-and-icon" readonly>
            </div>
            <div class="position-relative form-group">
                <button class="btn btn-danger mt-3 btn_default_style" type="button" onclick="app.calendar.notification_delete({'id': {{ $data->id }}, 'type': '{{ $data->type }}'});" data-toggle="tooltip" data-placement="right" title="@lang('Delete this notification')">
                    <i class="fa fa-trash"></i>&nbsp;
                    @lang('Delete')
                </button>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            setTimeout(() => {
                let textarea = document.getElementById('notification_edit_message');
                textarea.style.height = textarea.scrollHeight + 'px';
            }, 1000);
            let type = '{{ $data->language_type }}';
            document.getElementById('modal_notification_edit_title').innerHTML = type;
        });
    </script>
@endsection