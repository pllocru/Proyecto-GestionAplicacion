import $ from 'jquery';
import Swal from 'sweetalert2';

$(document).ready(function () {
    const modal = $('#addModal');
    const closeModalButton = $('#closeModalBtn');

    // Almacena los viveros seleccionados globalmente
    let selectedViveros = [];

    let selectedViverosEdit = []; // Estado global para los IDs seleccionados en el modal de edición

    // Mostrar modal
    $('#openModalBtn').click(function () {
        modal.removeClass('opacity-0 invisible').addClass('opacity-100 scale-100');
    });

    // Cerrar modal
    closeModalButton.click(function () {
        modal.removeClass('opacity-100 scale-100').addClass('opacity-0 invisible');
    });

    // Cerrar modal al hacer clic fuera del contenido del modal
    modal.click(function (event) {
        if ($(event.target).is(modal)) {
            cerrarModal();
        }
    });

    // Función para cerrar el modal
    function cerrarModal() {
        modal.removeClass('opacity-100 scale-100').addClass('opacity-0 invisible');
    }

    // === CARGA DINÁMICA DE EXPLOTACIONES ===
    $('#oidsocio').change(function () {
        const oidsocio = $(this).val();

        $.ajax({
            url: `/get-explotaciones`,
            type: 'GET',
            data: { oidsocio },
            success: function (data) {
                let options = data.length
                    ? data.map(exp => `<option value="${exp.oidexp}">${exp.explotacion}</option>`).join('')
                    : `<option value="">No hay datos</option>`;

                $('#oidexp').html(options).trigger('change'); // Carga y dispara cambio para parcelas
            }
        });
    });

    // === CARGA DINÁMICA DE PARCELAS ===
    $('#oidexp').change(function () {
        const oidexp = $(this).val();

        $.ajax({
            url: `/get-parcelas`,
            type: 'GET',
            data: { oidexp },
            success: function (data) {
                let options = data.length
                    ? data.map(parcela => `<option value="${parcela.oiducth}">${parcela.nombre_parcela}</option>`).join('')
                    : `<option value="">No hay datos</option>`;

                $('#oiducth').html(options).trigger('change');
            }
        });
    });

    // === CARGA DINÁMICA DE INVERNADEROS CON PAGINACIÓN Y SELECCIÓN PERSISTENTE ===
    function cargarViveros(url = '/get-viveros') {
        const oiducth = $('#oiducth').val();

        if (!oiducth) {
            $('#viverosContainer').html('<p class="text-gray-400">No hay parcelas seleccionadas.</p>');
            $('#pagination-viveros').html('');
            return;
        }

        $.ajax({
            url: url,
            type: 'GET',
            data: { oiducth: oiducth },
            success: function (data) {
                if (!data.viveros || data.viveros.length === 0) {
                    $('#viverosContainer').html('<p class="text-gray-400">No hay invernaderos disponibles.</p>');
                    $('#pagination-viveros').html('');
                    return;
                }

                // === Generar HTML de los viveros ===
                let viverosHTML = data.viveros.map(vivero => {
                    const isSelected = selectedViveros.includes(vivero.oidvivero);
                    return `
                                                                                                                                                                                                                                                <div 
                                                                                                                                                                                                                                                    class="flex items-center rounded-lg p-3 shadow-md cursor-pointer vivero-item 
                                                                                                                                                                                                                                                    ${isSelected ? 'bg-green-500' : 'bg-blue-500'} text-white"
                                                                                                                                                                                                                                                    data-id="${vivero.oidvivero}">
                                                                                                                                                                                                                                                    <span class="text-lg font-bold">${vivero.nombrevivero}</span>
                                                                                                                                                                                                                                                </div>`;
                }).join('');

                $('#viverosContainer').html(viverosHTML);

                // === Generar la paginación por números ===
                let paginationHTML = '';
                const totalPages = data.pagination.last_page;
                const currentPage = data.pagination.current_page;

                if (totalPages > 1) {
                    for (let i = 1; i <= totalPages; i++) {
                        paginationHTML += `
                                                                                                                                                                                                                                                        <button 
                                                                                                                                                                                                                                                            class="pagination-btn ${i === currentPage ? 'bg-blue-600 text-white' : 'bg-gray-300 text-black'} 
                                                                                                                                                                                                                                                            rounded-lg px-4 py-2 mx-1"
                                                                                                                                                                                                                                                        data-url="/get-viveros?page=${i}">
                                                                                                                                                                                                                                                            ${i}
                                                                                                                                                                                                                                                        </button>
                                                                                                                                                                                                                                                    `;
                    }
                }

                $('#pagination-viveros').html(paginationHTML);
            },
            error: function (xhr) {
                console.error('Error en la solicitud:', xhr.responseText);
                $('#viverosContainer').html('<p class="text-red-500">Error al cargar los invernaderos.</p>');
            }
        });
    }

    // === Cargar los viveros iniciales ===
    $('#oiducth').change(function () {
        cargarViveros();
    });

    // === Manejar la paginación mediante AJAX ===
    $(document).on('click', '.pagination-btn', function (e) {
        e.preventDefault();
        const url = $(this).data('url');
        cargarViveros(url);
    });

    // Manejar la selección de viveros (guardando la selección al cambiar de página)
    $(document).on('click', '.vivero-item', function () {
        const viveroId = $(this).data('id');

        if (selectedViveros.includes(viveroId)) {
            selectedViveros = selectedViveros.filter(id => id !== viveroId);
            $(this).removeClass('bg-green-500').addClass('bg-blue-500');
        } else {
            selectedViveros.push(viveroId);
            $(this).removeClass('bg-blue-500').addClass('bg-green-500');
        }

        console.log('Viveros seleccionados:', selectedViveros);  // Confirmar que se están seleccionando
    });


    function obtenerProvincia(lat, lng) {
        const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;

        $.getJSON(url, function (data) {
            if (data && data.address) {
                const provincia = data.address.province || data.address.county || 'Provincia no encontrada';
                console.log('Provincia:', provincia);
                $('#ubicacion').val(provincia); // Guardar la provincia en el campo oculto
            } else {
                console.error('No se pudo determinar la provincia.');
                $('#ubicacion').val('Provincia no encontrada');
            }
        }).fail(function () {
            console.error('Error en la solicitud a la API de Nominatim.');
            $('#ubicacion').val('Provincia no encontrada');
        });
    }

    // Obtener la ubicación del usuario
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                obtenerProvincia(lat, lng); // Llamar a la función para obtener la provincia
            },
            function (error) {
                console.error('Error al obtener la ubicación:', error);
                alert('No se pudo obtener la ubicación. Por favor, habilita la geolocalización.');
            }
        );
    } else {
        alert('La geolocalización no es compatible con este navegador.');
    }


    $('#formulario-productor').on('submit', function (e) {
        e.preventDefault();

        // Verificar que la ubicación esté asignada
        const ubicacion = $('#ubicacion').val();
        if (!ubicacion) {
            alert('No se pudo obtener la ubicación. Por favor, habilita la geolocalización.');
            return;
        }

        $('#oidviveroHidden').val(selectedViveros);

        const submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true).text('Procesando...');

        $.ajax({
            url: '{{ route("productores.store") }}',
            method: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                oidsocio: $('#oidsocio').val(),
                oidexp: $('#oidexp').val(),
                oiducth: $('#oiducth').val(),
                oidvivero: selectedViveros,
                ubicacion: ubicacion // Enviar la ubicación
            },
            success: function (response) {
                console.log('Respuesta del servidor:', response);

                if (response.status === 'success') {
                    // Almacenar el mensaje flash en el LocalStorage
                    localStorage.setItem('flash_message', response.message);

                    // Cerrar el modal
                    $('#addModal').removeClass('opacity-100 scale-100').addClass('opacity-0 invisible');

                    // Recargar la página
                    location.reload();
                } else {
                    alert('Error inesperado.');
                }
            },
            error: function (xhr) {
                console.error('Error en la solicitud:', xhr.responseText);
                alert('Error al guardar el registro.');
            },
            complete: function () {
                submitButton.prop('disabled', false).text('Crear Registro');
            }
        });
    });


    $(document).on('click', '#edit_viverosContainer .vivero-item', function () {
        const viveroId = $(this).data('id');

        if (selectedViverosEdit.includes(viveroId)) {
            // Si ya está seleccionado, lo eliminamos del array
            selectedViverosEdit = selectedViverosEdit.filter(id => id !== viveroId);
            $(this).removeClass('bg-green-500').addClass('bg-blue-500');
        } else {
            // Si no está seleccionado, lo agregamos al array
            selectedViverosEdit.push(viveroId);
            $(this).removeClass('bg-blue-500').addClass('bg-green-500');
        }

        console.log('Viveros seleccionados en edición:', selectedViverosEdit); // Confirmar que se están seleccionando correctamente
    });



    // === MOSTRAR EL MODAL DE EDICIÓN ===
    $(document).on('click', '.btn-editar', function () {
        const id = $(this).data('id');

        $.ajax({
            url: `/productores/${id}/edit`,
            method: 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    // Inicializar el array con los IDs de los viveros seleccionados
                    selectedViverosEdit = response.viverosSeleccionados;

                    // Cargar los datos en los campos del formulario
                    $('#edit_id').val(response.registro.id);

                    // Cargar el productor
                    let productoresHTML = response.productores.map(prod => `
                                      <option value="${prod.oidsocio}" ${prod.oidsocio === response.registro.oidsocio ? 'selected' : ''}>
                                                     ${prod.nombre} ${prod.apellidos}
                                                             </option>
                                                               `).join('');
                    $('#edit_oidsocio').html(productoresHTML).trigger('change');


                    // Cargar las explotaciones
                    let explotacionesHTML = response.explotaciones.map(exp => `
                                       <option value="${exp.oidexp}" ${exp.oidexp === response.registro.oidexp ? 'selected' : ''}>
                                                 ${exp.explotacion}
                                                         </option>
                                                            `).join('');
                    $('#edit_oidexp').html(explotacionesHTML).trigger('change');

                    // Cargar las parcelas
                    let parcelasHTML = response.parcelas.map(parcela => `
                                        <option value="${parcela.oiducth}" ${parcela.oiducth === response.registro.oiducth ? 'selected' : ''}>
                                                   ${parcela.nombre_parcela}
                                                                 </option>
                                                      `).join('');
                    $('#edit_oiducth').html(parcelasHTML).trigger('change');

                    // Cargar los viveros en el modal de edición
                    cargarViverosEditModal(response.registro.oiducth, selectedViverosEdit);

                    // Mostrar el modal
                    $('#editModal').removeClass('opacity-0 invisible').addClass('opacity-100 scale-100');
                }
            },
            error: function () {
                alert('Error al obtener los datos del registro.');
            }
        });
    });

    // === CARGA DINÁMICA DE VIVEROS CON PAGINACIÓN Y SELECCIÓN ===
    function cargarViverosEditModal(oiducth, viverosSeleccionados = [], url = '/get-viveros') {
        $.ajax({
            url: url,
            method: 'GET',
            data: { oiducth: oiducth },
            success: function (data) {
                if (!data.viveros || data.viveros.length === 0) {
                    $('#edit_viverosContainer').html('<p class="text-gray-400">No hay invernaderos disponibles.</p>');
                    $('#pagination-viveros-edit').html('');
                    return;
                }

                // Generar HTML de los viveros
                let viverosHTML = data.viveros.map(vivero => {
                    const isSelected = selectedViverosEdit.includes(vivero.oidvivero); // Verificar si está seleccionado
                    return `
                                                                                                                        <div 
                                                                                                                            class="flex items-center rounded-lg p-3 shadow-md cursor-pointer vivero-item 
                                                                                                                            ${isSelected ? 'bg-green-500' : 'bg-blue-500'} text-white"
                                                                                                                            data-id="${vivero.oidvivero}">
                                                                                                                            <span class="text-lg font-bold">${vivero.nombrevivero}</span>
                                                                                                                        </div>`;
                }).join('');

                $('#edit_viverosContainer').html(viverosHTML);

                // Generar la paginación
                let paginationHTML = '';
                const totalPages = data.pagination.last_page;
                const currentPage = data.pagination.current_page;

                if (totalPages > 1) {
                    for (let i = 1; i <= totalPages; i++) {
                        paginationHTML += `
                                                                                                                            <button 
                                                                                                                                class="pagination-btn-edit ${i === currentPage ? 'bg-yellow-500 text-white' : 'bg-gray-300 text-black'} 
                                                                                                                                rounded-lg px-4 py-2 mx-1"
                                                                                                                                data-url="/get-viveros?page=${i}">
                                                                                                                                ${i}
                                                                                                                            </button>`;
                    }
                }

                $('#pagination-viveros-edit').html(paginationHTML);
            },
            error: function (xhr) {
                console.error('Error en la solicitud:', xhr.responseText);
                $('#edit_viverosContainer').html('<p class="text-red-500">Error al cargar los invernaderos.</p>');
            }
        });
    }

    // === PAGINACIÓN DE VIVEROS EN EL MODAL DE EDICIÓN ===
    $(document).on('click', '.pagination-btn-edit', function (e) {
        e.preventDefault();
        const url = $(this).data('url');
        const oiducth = $('#edit_oiducth').val();

        // Cargar la nueva página de invernaderos, manteniendo los seleccionados
        cargarViverosEditModal(oiducth, selectedViverosEdit, url);
    });

    $('#formulario-editar').on('submit', function (e) {
        e.preventDefault();

        // Pasar los IDs seleccionados al campo oculto
        $('#edit_oidviveroHidden').val(selectedViverosEdit);

        const submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true).text('Procesando...');

        $.ajax({
            url: `/productores/${$('#edit_id').val()}`,
            method: 'PUT',
            data: {
                _token: $('input[name="_token"]').val(),
                oidsocio: $('#edit_oidsocio').val(),
                oidexp: $('#edit_oidexp').val(),
                oiducth: $('#edit_oiducth').val(),
                oidvivero: selectedViverosEdit // Enviar los IDs seleccionados
            },
            success: function (response) {
                console.log('Respuesta del servidor:', response);

                if (response.status === 'success') {
                    // Almacenar el mensaje flash en el LocalStorage
                    localStorage.setItem('flash_message', response.message);

                    // Cerrar el modal
                    $('#editModal').removeClass('opacity-100 scale-100').addClass('opacity-0 invisible');

                    // Recargar la página
                    location.reload();
                } else {
                    alert('Error inesperado.');
                }
            },
            error: function (xhr) {
                console.error('Error en la solicitud:', xhr.responseText);
                alert('Error al guardar los cambios.');
            },
            complete: function () {
                submitButton.prop('disabled', false).text('Guardar Cambios');
            }
        });
    });

    $(document).ready(function () {
        // Mostrar el mensaje flash si existe en el LocalStorage
        const flashMessage = localStorage.getItem('flash_message');
        if (flashMessage) {
            Swal.fire({
                title: 'Éxito',
                text: flashMessage,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });

            // Eliminar el mensaje flash del LocalStorage para evitar que se repita
            localStorage.removeItem('flash_message');
        }

        // === ELIMINAR REGISTRO ===
        $(document).on('click', '.btn-eliminar', function () {
            const id = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/productores/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: $('input[name="_token"]').val()
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                // Almacenar el mensaje flash en el LocalStorage
                                localStorage.setItem('flash_message', response.message);

                                // Recargar la página para mostrar el mensaje flash
                                location.reload();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Error inesperado.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al eliminar el registro.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
    });

    // Botón "Cancelar" en el modal de creación
    $('#closeModalBtn').click(function () {
        $('#addModal').removeClass('opacity-100 scale-100').addClass('opacity-0 invisible');
    });

    // Botón "Cancelar" en el modal de edición
    $('#closeEditModalBtn').click(function () {
        $('#editModal').removeClass('opacity-100 scale-100').addClass('opacity-0 invisible');
    });


});

