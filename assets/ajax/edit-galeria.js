Dropzone.autoDiscover = false;

    let coverFile = null; // Archivo de la imagen destacada
    let galleryFiles = []; // Nuevas imágenes de la galería
    let deletedFiles = []; // Imágenes marcadas para eliminación

    // Configurar Dropzone para la imagen destacada
    const coverDropzone = new Dropzone("#edit-dropzone-cover", {
        url: "/dummy", // No procesar automáticamente
        autoProcessQueue: false,
        acceptedFiles: "image/*",
        maxFiles: 1,
        addRemoveLinks: true,
        dictDefaultMessage: "Arrastra aquí la imagen destacada",
        init: function () {
            this.on("addedfile", function (file) {
                coverFile = file; // Guardar el archivo de la imagen destacada
            });
            this.on("removedfile", function () {
                coverFile = null; // Limpiar la selección
            });

            // Precargar archivo existente
            const mockFile = { name: $("#edit-dropzone-cover").data("filename"), size: 12345 };
            this.emit("addedfile", mockFile);
            this.emit("thumbnail", mockFile, $("#edit-dropzone-cover").data("url"));
            this.files.push(mockFile);
            mockFile.previewElement.classList.add("dz-success", "dz-complete");
        },
    });

    // Configurar Dropzone para las imágenes adicionales
    const galleryDropzone = new Dropzone("#edit-dropzone-imagenes", {
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
                if (file.existing) {
                    deletedFiles.push(file.name); // Agregar archivos existentes eliminados
                } else {
                    galleryFiles = galleryFiles.filter(f => f !== file); // Remover nuevos archivos
                }
            });

            // Precargar archivos existentes
            const existingImages = JSON.parse($("#edit-dropzone-imagenes").data("existing-images"));
            existingImages.forEach(image => {
                const mockFile = { name: image.name, size: 12345, existing: true };
                this.emit("addedfile", mockFile);
                this.emit("thumbnail", mockFile, image.url);
                this.files.push(mockFile);
                mockFile.previewElement.classList.add("dz-success", "dz-complete");
            });
        },
    });
$(document).ready(function () {
    

    // Manejar el envío del formulario
    $("#edit-galeria-form").on("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Agregar imagen destacada si se cambia
        if (coverFile) {
            formData.append("cover", coverFile);
        }

        // Agregar nuevas imágenes al FormData
        galleryFiles.forEach((file, index) => {
            formData.append(`imagenes[${index}]`, file);
        });

        // Agregar imágenes eliminadas al FormData
        deletedFiles.forEach((file, index) => {
            formData.append(`deleted_images[${index}]`, file);
        });

        // Enviar la solicitud AJAX
        $.ajax({
            url: "controllers/modelo-galerias.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                const res = JSON.parse(response);
                if (res.success) {
                    Swal.fire("Éxito", "Galería actualizada correctamente", "success").then(() => {
                        window.location.href = "galerias.php";
                    });
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Hubo un problema con la solicitud", "error");
            },
        });
    });
});
