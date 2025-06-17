<script>
    $(document).ready(function() {
        $('.selectpicker').selectpicker();

        let dataId = window.location.pathname.split('/').pop();
        let url = '{{ route('master.guru.show', ':id') }}'.replace(':id', dataId);

        AjaxHandler.sendGetRequest(url, function(response) {
            if (response.status === 200 && response.data) {
                let d = response.data;

                $('#editForm').attr('data-id', d.id_guru);

                if (d.foto) {
                    let fotoPath = "{{ asset('') }}" + 'uploads/' + d.foto;
                    $('#link-container').html(
                        `<a href="${fotoPath}" class="d-flex align-items-center" id="lihat_foto" target="_blank">
                            <i class="fas fa-eye me-2"></i>
                            <span>Lihat Foto</span>
                        </a>`
                    );
                } else {
                    $('#link-container').empty();
                }

                $('.selectpicker').selectpicker('refresh');
            } else {
                ResponseHandler.handleError("Data tidak ditemukan.");
            }
        });
    });
</script>


<script>
    $("#editForm").on("submit", function(e) {
        e.preventDefault();
        let form = $(this);
        let dataId = form.attr('data-id');
        let url = '{{ route('master.guru.update', ':id') }}'.replace(':id', dataId);

        AjaxHandler.sendUpdateRequest(url, this, function() {
            window.location.href = "{{ route('master.guru.index') }}";
        }, function(response) {
            let errors = response.responseJSON?.data || {};
            ResponseHandler.handleValidationErrors(errors, form);
        });
    });
</script>
