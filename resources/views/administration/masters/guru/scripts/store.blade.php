<!-- Handler submit untuk form -->
<script defer>
    // Handler submit tetap tidak berubah
    $("#createForm").on("submit", function(e) {
        e.preventDefault();
        let form = $(this);
        let url = "{{ route('master.guru.store') }}";

        // Kirimkan request Ajax hanya jika form valid
        AjaxHandler.sendStoreRequest(url, this, function() {
            // Redirect setelah berhasil
            window.location.href = "{{ route('master.guru.index') }}";
        }, function(response) {
            let errors = response.responseJSON?.data || {}; // Pastikan response adalah object
            ResponseHandler.handleValidationErrors(errors, form);
        });
    });
</script>
