@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('head')
@endsection

@section('content')
    <table class="table table-hover">
        <tbody>
            @isset($data)
                @foreach ($data as $item)
                    <tr data-id="{{ $item->id }}">
                        <td>
                            <span class="file_name" title="{{ $item->file_name }}">{{ $item->file_name }}</span>
                        </td>
                        <td>
                            <span class="file_size">{{ lib()->do->get_filesize($item->file_size) }}</span>
                        </td>
                        <td class="btn_container">
                            <div class="action_buttons">
                                <a class="btn px-1" href="{{ img_url($item->file_path) }}" target="_blank" title="@lang('View')" data-toggle="tooltip" data-placement="top">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <button class="btn px-1" onclick="lib.do.download_file('{{ img_url($item->file_path) }}', '{{ $item->file_name}}', this);" title="@lang('Download')" data-toggle="tooltip" data-placement="top">
                                    <i class="fa fa-download"></i>
                                </button>
                                <button class="btn px-1" onclick="app.subscription.attachment_delete('{{ $item->id }}');" title="@lang('Delete')" data-toggle="tooltip" data-placement="top">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
@endsection
