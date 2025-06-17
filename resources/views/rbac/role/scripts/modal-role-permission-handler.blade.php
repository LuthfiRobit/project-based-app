<script>
    // Fungsi untuk menangani klik tombol simpan pada modal permission
    $('#permissionForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting the default way

        const roleId = $('#permissionForm').attr('data-id'); // Ambil role ID dari data-id pada form
        const selectedPermissions = getSelectedPermissions(); // Ambil ID permissions yang dipilih

        // Kirim data ke server menggunakan Ajax
        saveRolePermissions(roleId, selectedPermissions);
    });

    // Fungsi untuk mengumpulkan ID permission yang dipilih
    function getSelectedPermissions() {
        const selectedPermissions = [];
        const checkboxes = document.querySelectorAll(
            '#permissions_list input[type="checkbox"][name="permissions[]"]:checked');

        checkboxes.forEach(checkbox => {
            selectedPermissions.push(checkbox.value); // Ambil id_permission dari value checkbox
        });

        return selectedPermissions;
    }

    // Fungsi untuk mengirimkan data role_permission ke server
    function saveRolePermissions(roleId, permissions) {
        const url = '{{ route('rbac.role.store-role-permission', ':id') }}'.replace(':id', roleId);

        let formData = new FormData();
        permissions.forEach(permission => formData.append('permissions[]', permission));
        formData.append('_token', '{{ csrf_token() }}');

        AjaxHandler.sendRequest(
            url,
            'POST',
            formData,
            function(response) {
                ResponseHandler.handleSuccess("Permissions role berhasil disimpan.");
                $('#modalPermission').modal('hide');
                $('#example').DataTable().ajax.reload(); // reload datatable kalau perlu
            },
            function(xhr) {
                let errors = xhr.responseJSON?.data || {};
                ResponseHandler.handleValidationErrors(errors, null);
            }
        );
    }
</script>
