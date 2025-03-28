@if (lib()->config->xeno_send_data)
  <script>
    window._xenoSettings = {
      identify: function() {
        @if (Auth::check() && lib()->config->xeno_send_data && lib()->user->me->wp_user_id) return {

          /* Mandatory metadata */
          id: {{ lib()->user->me->wp_user_id }},                // User ID (Mandatory)
          name: '{{  lib()->user->me->name  }}',              // Your user's full name

          /* Recommended metadata */
          email: '{{ lib()->user->me->email }}',             // Your user's email address

        };
        @else return null;
        @endif
      },
      key: "{{ lib()->config->xeno_public_key }}",
    };
  </script>
  <script src="https://cdn.xeno.app/chat_loader.js" async="true"></script>
@endif
