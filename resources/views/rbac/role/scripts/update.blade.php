<script defer>
    $("#modalEdit").on("show.bs.modal", function() {
        $(this).removeAttr('aria-hidden');
        let form = $('#editForm');
        form.find('.invalid-feedback').remove();
        form.find('.form-control').removeClass('is-invalid');
    });

    $("#editForm").on("submit", function(e) {
        e.preventDefault();
        let form = $(this);
        let dataId = $(this).attr('data-id');
        let url = `{{ route('rbac.role.update', ':id') }}`.replace(':id', dataId);

        AjaxHandler.sendUpdateRequest(url, this, function() {
            $('#modalEdit').modal('hide');
            $('#example').DataTable().ajax.reload();
        }, function(response) {
            let errors = response.responseJSON?.data || {}; // Ensure an object is passed
            ResponseHandler.handleValidationErrors(errors, form);
        });
    });
</script>
