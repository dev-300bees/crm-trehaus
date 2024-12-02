Dropzone.autoDiscover = false;

    let coverFile = null; // Variable para la imagen destacada
    let galleryFiles = []; // Array para las imágenes de la galería

    // Configurar Dropzone para la imagen destacada
    const coverDropzone = new Dropzone("#dropzone-cover", {
        url: "/dummy", // No procesar automáticamente
        autoProcessQueue: false,
        acceptedFiles: "image/*",
        maxFiles: 1,
        addRemoveLinks: true,
        dictDefaultMessage: "Arrastra aquí la imagen destacada",
        init: function () {
            this.on("addedfile", function (file) {
                coverFile = file; // Guardar el archivo seleccionado
            });
            this.on("removedfile", function () {
                coverFile = null; // Limpiar la selección
            });
        }
    });

    // Configurar Dropzone para las imágenes de la galería
    const galleryDropzone = new Dropzone("#dropzone-imagenes", {
        url: "/dummy", // No procesar automáticamente
        autoProcessQueue: false,
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        dictDefaultMessage: "Arrastra aquí las imágenes de la galería",
        init: function () {
            this.on("addedfile", function (file) {
                galleryFiles.push(file); // Agregar el archivo al array
            });
            this.on("removedfile", function (file) {
                galleryFiles = galleryFiles.filter(f => f !== file); // Eliminar el archivo del array
            });
        }
    });

$(document).ready(function () {
    // Manejar el envío del formulario
    $("#form-galeria").on("submit", function (e) {
        e.preventDefault();

        const formData = new FormData();

        // Agregar los datos del formulario
        formData.append("action", "crear");
        formData.append("nombre", $("#nombre").val());
        formData.append("fecha", $("#fecha").val());

        // Agregar la imagen destacada al FormData
        if (coverFile) {
            formData.append("cover", coverFile);
        }

        // Agregar las imágenes de la galería al FormData
        galleryFiles.forEach((file, index) => {
            formData.append(`imagenes[${index}]`, file);
        });

        // Enviar la solicitud AJAX
        $.ajax({
            url: "controllers/modelo-galerias.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                const res = JSON.parse(response);
                if (res.success) {
                    Swal.fire("Éxito", "Galería creada correctamente", "success").then(() => {
                        window.location.href = "galerias.php";
                    });
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Hubo un problema con la solicitud", "error");
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
    });
});
