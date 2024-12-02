$(document).ready(function () {
    // Crear Evento
    $('#createEventForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'controllers/modelo-eventos.php?action=create',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    Swal.fire('Éxito', res.message, 'success').then(() => {
                        window.location.href = 'eventos.php';
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

    // Editar Evento
    $('#editEventForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'controllers/modelo-eventos.php?action=edit',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    Swal.fire('Éxito', res.message, 'success').then(() => {
                        window.location.href = 'eventos.php';
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

    // Eliminar Evento
    $('.delete-event').on('click', function () {
        const eventId = $(this).data('id');

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
                    url: `controllers/modelo-eventos.php?action=delete&id=${eventId}`,
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
