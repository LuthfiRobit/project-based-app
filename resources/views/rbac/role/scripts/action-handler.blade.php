<script>
    // ========================
    // = Handle Centang Semua =
    // ========================
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('check-all')) {
            const group = e.target.getAttribute('data-group');
            document.querySelectorAll(`.${group}`).forEach(cb => {
                cb.checked = e.target.checked;
            });
        }
    });

    // ===========================
    // = Tampilkan Data Role dan =
    // = Buat Checkbox Accordion =
    // ===========================
    function handleActionPermission(roleId) {
        const url = '{{ route('rbac.role.list-role-permission', ':id') }}'.replace(':id', roleId);

        AjaxHandler.sendGetRequest(url, function(response) {
            if (response.status === 200 && response.data) {
                const {
                    role,
                    permissions,
                    role_permissions
                } = response.data;

                // Tampilkan info role
                document.getElementById('permissionForm').setAttribute('data-id', roleId);
                document.getElementById('detail_role_name').textContent = role.role_name || 'N/A';
                document.getElementById('detail_role_description').textContent = role.role_description || 'N/A';

                // Buat list permission
                createCheckboxList(permissions, role_permissions);

                // Tampilkan modal
                $('#modalPermission').modal('show');
            } else {
                ResponseHandler.handleError("Data tidak ditemukan.");
            }
        });
    }

    // =======================
    // = Buat Checkbox List  =
    // =======================
    function createCheckboxList(permissions, rolePermissions) {
        const permissionsList = document.getElementById('permissions_list');
        permissionsList.innerHTML = '';

        const accordion = document.createElement('div');
        accordion.classList.add('accordion');
        accordion.id = 'accordionPermissions';

        const row = document.createElement('div');
        row.classList.add('row', 'row-cols-1', 'row-cols-md-3', 'g-3');

        const groupedPermissions = groupPermissionsByPrefix(permissions);

        Object.keys(groupedPermissions).forEach(prefix => {
            createAccordionPermissionGroup(groupedPermissions[prefix], prefix, row, rolePermissions);
        });

        accordion.appendChild(row);
        permissionsList.appendChild(accordion);
    }

    // ===============================
    // = Buat Accordion per Prefix   =
    // ===============================
    function createAccordionPermissionGroup(permissionGroup, prefix, container, rolePermissions) {
        const groupKey = prefix.replaceAll('.', '-');
        const headerId = `heading-${groupKey}`;
        const collapseId = `collapse-${groupKey}`;
        const groupClass = `group-${groupKey}`;

        const accordionItem = document.createElement('div');
        accordionItem.classList.add('col');

        accordionItem.innerHTML = `
            <div class="accordion-item" data-group-key="${prefix}">
                <h2 class="accordion-header" id="${headerId}">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#${collapseId}"
                        aria-expanded="false" aria-controls="${collapseId}">
                        ${prefix}
                    </button>
                </h2>
                <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="${headerId}">
                    <div class="accordion-body" data-permission-group="${groupClass}">
                        <div class="form-check mb-2">
                            <input class="form-check-input check-all" type="checkbox" id="checkAll-${groupKey}" data-group="${groupClass}">
                            <label class="form-check-label fw-semibold" for="checkAll-${groupKey}">Centang Semua</label>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const accordionBody = accordionItem.querySelector('.accordion-body');

        permissionGroup.forEach(permission => {
            const checkboxDiv = createCheckbox(permission, groupClass, rolePermissions);
            accordionBody.appendChild(checkboxDiv);
        });

        container.appendChild(accordionItem);
    }

    // ===========================
    // = Buat Checkbox Satuan    =
    // ===========================
    function createCheckbox(permission, groupClass, rolePermissions) {
        const checkboxDiv = document.createElement('div');
        checkboxDiv.classList.add('form-check', 'mb-2');

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = 'permissions[]'; // Ini wajib agar bisa difilter
        checkbox.classList.add('form-check-input', groupClass);
        checkbox.id = `check-${permission.id_permission}`;
        checkbox.value = permission.id_permission;

        if (rolePermissions.includes(permission.id_permission)) {
            checkbox.checked = true;
        }

        const label = document.createElement('label');
        label.classList.add('form-check-label');
        label.setAttribute('for', checkbox.id);
        label.textContent = permission.permission_name;

        checkboxDiv.appendChild(checkbox);
        checkboxDiv.appendChild(label);

        return checkboxDiv;
    }

    // ===========================
    // = Group Permission Format =
    // ===========================
    function groupPermissionsByPrefix(permissions) {
        return permissions.reduce((grouped, permission) => {
            const parts = permission.permission_name.split('.');
            const prefix = parts.length >= 2 ? parts[0] + '.' + parts[1] : parts[0];
            if (!grouped[prefix]) grouped[prefix] = [];
            grouped[prefix].push(permission);
            return grouped;
        }, {});
    }

    // ===========================
    // = Fungsi untuk menangani action 'action_show =
    // ===========================
    function handleActionShow(url) {
        AjaxHandler.sendGetRequest(url, function(response) {
            if (response.status === 200 && response.data) {
                $('#detail_role_name').text(response.data.role_name || 'N/A');
                $('#detail_role_description').text(response.data.role_description || 'N/A');
                $('#modalDetail').modal('show');
            } else {
                ResponseHandler.handleError("Data tidak ditemukan.");
            }
        });
    }

    // ===========================
    // = Fungsi untuk menangani action 'action_edit =
    // ===========================
    function handleActionEdit(url) {
        AjaxHandler.sendGetRequest(url, function(response) {
            if (response.status === 200 && response.data) {
                $('#editForm').attr('data-id', response.data.id_role);
                $('.selectpicker').selectpicker('refresh');
                $('#modalEdit').modal('show');
            } else {
                ResponseHandler.handleError("Data tidak ditemukan.");
            }
        });
    }

    // ========================
    // = Event untuk Aksi     =
    // ========================
    $('#example').on('click', '.dropdown-item', function() {
        const action = $(this).data('action');
        const dataId = $(this).data('id');

        if (!dataId) {
            ResponseHandler.handleError("ID tidak ditemukan!");
            return;
        }

        switch (action) {
            case 'action_show':
                handleActionShow('{{ route('rbac.role.show', ':id') }}'.replace(':id', dataId));
                break;
            case 'action_edit':
                handleActionEdit('{{ route('rbac.role.show', ':id') }}'.replace(':id', dataId));
                break;
            case 'action_permission':
                handleActionPermission(dataId);
                break;
        }
    });
</script>
