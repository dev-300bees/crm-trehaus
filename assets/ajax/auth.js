$(document).ready(function () {
    // Manejo del envío del formulario de login
    $('#loginForm').on('submit', function (e) {
        e.preventDefault(); 

        const formData = $(this).serialize(); 

        $.ajax({
            url: '/controllers/modelo-login.php', 
            type: 'POST',
            data: formData,
            success: function (response) {
                const res = JSON.parse(response); 

                if (res.status === 'success') {
                    window.location.href = 'index.php'; 
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
            }
        });
    });
});
