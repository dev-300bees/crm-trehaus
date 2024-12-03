$(document).ready(function () {
    let urlIndex = $(".video-url").length; // Inicializamos con la cantidad de campos existentes

    // Agregar un nuevo campo de URL
    $("#videos-container").on("click", ".btn-add-url", function () {
        const newField = `
            <div class="input-group mb-3" id="video-group-${urlIndex}">
                <input type="url" class="form-control video-url" name="new_video_urls[]" required>
                <button type="button" class="btn btn-danger btn-remove-url" data-index="${urlIndex}">-</button>
            </div>`;
        $("#videos-container").append(newField);
        urlIndex++; // Incrementamos el índice
    });

    // Eliminar un campo de URL
    $("#videos-container").on("click", ".btn-remove-url", function () {
        $(this).closest(".input-group").remove(); // Eliminamos el grupo de entrada completo
    });

    // Manejar el envío del formulario
    $("#form-video").on("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Validar que haya al menos una URL
        const videoUrls = $(".video-url").map((_, el) => $(el).val()).get();
        if (videoUrls.length === 0 || videoUrls.some(url => url.trim() === "")) {
            Swal.fire("Error", "Debe haber al menos una URL válida.", "error");
            return;
        }

        // Enviar la solicitud AJAX
        $.ajax({
            url: "controllers/modelo-videos.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                const res = JSON.parse(response);
                if (res.success) {
                    Swal.fire("Éxito", "Video actualizado correctamente.", "success").then(() => {
                        window.location.href = "videos.php";
                    });
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Hubo un problema con la solicitud.", "error");
            },
        });
    });

    $(".btn-delete-video").on("click", function () {
        const videoId = $(this).data("id");
    
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará el video y todas sus URLs asociadas.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'controllers/modelo-videos.php',
                    method: 'POST',
                    data: { action: 'eliminar', id: videoId },
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.success) {
                            Swal.fire('Eliminado', 'El video ha sido eliminado correctamente.', 'success').then(() => {
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
