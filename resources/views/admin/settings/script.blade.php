@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/settings')

@section('head')
@endsection

@section('content')

    <div class="main-card mb-3">
        <form action="{{ route('admin/settings/script/update') }}" id="settings_script_update_form" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <h5 class="card-header">@lang('Header')</h5>
                        <div class="card-body p-0">
                            <textarea class="form-control border-white" name="header" rows="10">{{ $data->header }}</textarea>
                        </div>
                    </div>
                    <br>
                    <div class="card">
                        <h5 class="card-header">@lang('Footer')</h5>
                        <div class="card-body p-0">
                            <textarea class="form-control border-white" name="footer" rows="10">{{ $data->footer }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">
                        <h5 class="card-header">@lang('Custom CSS')</h5>
                        <div class="card-body p-0">
                            <textarea class="form-control border-white" name="css" rows="24">{{ $data->css }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-right mt-3">
                <button class="btn btn-primary" type="submit" onclick="app.settings.script.update(this);">
                    <i class="fa fa-save"></i>&nbsp;
                    @lang('Save')
                </button>
            </div>
        </form>
    </div>


    <script>
        $(document).ready(function() {

            // Accept Tab key for textarea
            $('textarea').on('keydown', function(e) {
                if (e.key == 'Tab') {
                    e.preventDefault();
                    var start = this.selectionStart;
                    var end = this.selectionEnd;

                    // set textarea value to: text before caret + tab + text after caret
                    this.value = this.value.substring(0, start) +
                        "\t" + this.value.substring(end);

                    // put caret at right position again
                    this.selectionStart =
                        this.selectionEnd = start + 1;
                }
            });
        });
    </script>

@endsection
