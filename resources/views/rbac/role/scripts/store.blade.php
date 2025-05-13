<script defer>
    $("#modalCreate").on("show.bs.modal", function() {
        $(this).removeAttr('aria-hidden');
        let form = $('#createForm');
        form[0].reset();
        form.find('.selectpicker').selectpicker('refresh');
        form.find('.invalid-feedback').remove();
        form.find('.form-control').removeClass('is-invalid');
    });

    $("#createForm").on("submit", function(e) {
        e.preventDefault();
        let form = $(this);
        let url = "{{ route('rbac.role.store') }}";

        AjaxHandler.sendStoreRequest(url, this, function() {
            $('#modalCreate').modal('hide');
            $('#example').DataTable().ajax.reload();
        }, function(response) {
            let errors = response.responseJSON?.data || {}; // Ensure an object is passed
            ResponseHandler.handleValidationErrors(errors, form);
        });
    });
</script>
