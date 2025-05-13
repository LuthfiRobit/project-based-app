<script>
    // Fungsi untuk mengupdate role permissions (centang checkbox sesuai role permissions)
    function updateRolePermissions(rolePermissions) {
        const checkboxes = document.querySelectorAll('#permissions_list input[type="checkbox"]');
        checkboxes.forEach(checkbox => checkbox.checked = false); // Uncheck all checkboxes first
        rolePermissions.forEach(permissionId => {
            const checkbox = Array.from(checkboxes).find(checkbox => checkbox.value == permissionId);
            if (checkbox) checkbox.checked = true; // Check the relevant checkboxes
        });
    }

    // Fungsi untuk membuat daftar checkbox berdasarkan data permissions
    function createCheckboxList(permissions, rolePermissions) {
        const permissionsList = document.getElementById('permissions_list');
        permissionsList.innerHTML = ''; // Clear previous checkboxes

        const groupedPermissions = groupPermissionsByPrefix(permissions);

        // Create checkboxes in grouped columns based on prefix
        Object.keys(groupedPermissions).forEach(prefix => {
            createPermissionColumns(groupedPermissions[prefix], permissionsList);
        });

        // Update checkboxes according to the role permissions
        updateRolePermissions(rolePermissions);
    }

    // Fungsi untuk mengelompokkan permissions berdasarkan dua kata depan (prefix)
    function groupPermissionsByPrefix(permissions) {
        return permissions.reduce((grouped, permission) => {
            const prefix = permission.permission_name.split('-').slice(0, 2).join('-');
            if (!grouped[prefix]) grouped[prefix] = [];
            grouped[prefix].push(permission);
            return grouped;
        }, {});
    }

    // Fungsi untuk membuat kolom-kolom checkbox berdasarkan permission group
    function createPermissionColumns(permissionGroup, permissionsList) {
        const itemsPerColumn = 10; // Set maximum items per column
        const totalColumns = Math.ceil(permissionGroup.length / itemsPerColumn);

        for (let col = 0; col < totalColumns; col++) {
            const colDiv = document.createElement('div');
            colDiv.classList.add('col-xl-3', 'col-lg-3', 'col-md-6', 'col-sm-12', 'p-2', 'border', 'border-primary',
                'rounded');

            const checkboxContainer = document.createElement('div');
            checkboxContainer.classList.add('form-check-group');

            const startIndex = col * itemsPerColumn;
            const subset = permissionGroup.slice(startIndex, Math.min(startIndex + itemsPerColumn, permissionGroup
                .length));

            subset.forEach(permission => {
                const checkboxDiv = createCheckbox(permission);
                checkboxContainer.appendChild(checkboxDiv);
            });

            colDiv.appendChild(checkboxContainer);
            permissionsList.appendChild(colDiv);
        }
    }

    // Fungsi untuk membuat checkbox input dan label
    function createCheckbox(permission) {
        const checkboxDiv = document.createElement('div');
        checkboxDiv.classList.add('form-check', 'mb-2');

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.classList.add('form-check-input');
        checkbox.id = `check-${permission.id_permission}`;
        checkbox.value = permission.id_permission;

        const label = document.createElement('label');
        label.classList.add('form-check-label');
        label.setAttribute('for', checkbox.id);
        label.textContent = permission.permission_name;

        checkboxDiv.appendChild(checkbox);
        checkboxDiv.appendChild(label);

        return checkboxDiv;
    }

    // Fungsi untuk menangani klik pada item dropdown Aksi
    $('#example').on('click', '.dropdown-item', function() {
        const action = $(this).data('action');
        const dataId = $(this).data('id');
        const url = '{{ route('rbac.role.show', ':id') }}'.replace(':id', dataId);

        if (!dataId) {
            ResponseHandler.handleError("ID tidak ditemukan!");
            return;
        }

        // Handle each action based on the selected dropdown item
        switch (action) {
            case 'action_show':
                handleActionShow(url);
                break;
            case 'action_edit':
                handleActionEdit(url);
                break;
            case 'action_permission':
                handleActionPermission(dataId);
                break;
            default:
                break;
        }
    });

    // Fungsi untuk menangani action 'action_show'
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

    // Fungsi untuk menangani action 'action_edit'
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

    // Fungsi untuk menangani action 'action_permission'
    function handleActionPermission(dataId) {
        const url = '{{ route('rbac.role.list-role-permission', ':id') }}'.replace(':id', dataId);
        AjaxHandler.sendGetRequest(url, function(response) {
            if (response.status === 200 && response.data) {
                $('#permissionForm').attr('data-id', dataId);
                $('#modalPermission #detail_role_name').text(response.data.role.role_name || 'N/A');
                $('#modalPermission #detail_role_description').text(response.data.role.role_description ||
                    'N/A');

                const permissions = response.data.permissions;
                const rolePermissions = response.data.role_permissions;

                createCheckboxList(permissions, rolePermissions);
                $('#modalPermission').modal('show');
            } else {
                ResponseHandler.handleError("Data tidak ditemukan.");
            }
        });
    }
</script>
