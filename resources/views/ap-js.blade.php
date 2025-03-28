<script>
    // Admin Panel
    var _app_data = {
        url: "{{ url('/') }}/",
        cdn_url: "{{ lib()->get->cdn_url() }}",
        version: "{{ \App\Models\SettingsModel::get_arr(1)->versions_name }}",
        title: "{{ config('app.name') }}",
        lang: {
            "None Selected": "@lang('None Selected')",
        },
    };
</script>
