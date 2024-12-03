$(document).ready(function () {
    // Manejar la eliminación de una galería
    $(".btn-delete-galeria").on("click", function (e) {
        e.preventDefault();

        const galeriaId = $(this).data("id");

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará la galería y todos sus archivos asociados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar la solicitud AJAX para eliminar
                $.ajax({
                    url: 'controllers/modelo-galerias.php',
                    method: 'POST',
                    data: {
                        action: 'eliminar',
                        id: galeriaId
                    },
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.success) {
                            Swal.fire(
                                'Eliminado',
                                'La galería ha sido eliminada correctamente.',
                                'success'
                            ).then(() => {
                                // Recargar la página o eliminar la fila correspondiente
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Hubo un problema con la solicitud.', 'error');
                    }
                });
            }
        });
    });
});
