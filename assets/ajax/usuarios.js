$(document).ready(function () {
    // Crear usuario
    $('#createUserForm').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: 'controllers/modelo-usuarios.php?action=create',
            type: 'POST',
            data: formData,
            success: function (response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    Swal.fire('Éxito', res.message, 'success').then(() => {
                        window.location.href = 'usuarios.php';
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
            }
        });
    });

    // Editar usuario
    $('#editUserForm').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: 'controllers/modelo-usuarios.php?action=edit',
            type: 'POST',
            data: formData,
            success: function (response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    Swal.fire('Éxito', res.message, 'success').then(() => {
                        window.location.href = 'usuarios.php';
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
            }
        });
    });

    // Eliminar usuario
    $('.delete-user').on('click', function () {
        const userId = $(this).data('id');

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `controllers/modelo-usuarios.php?action=delete&id=${userId}`,
                    type: 'GET',
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            Swal.fire('Eliminado', res.message, 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
                    }
                });
            }
        });
    });
});
