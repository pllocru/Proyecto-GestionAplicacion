<!-- Modal Ver Tabla -->
<div x-data="modalAplicaciones" x-on:open-modal.window="if ($event.detail.id === 'modalAplicaciones') openModal()"
    x-show="showAplicaciones" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-5xl overflow-y-auto max-h-[90vh] relative">
        <button @click="showAplicaciones = false"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✖️</button>

        <h2 class="text-2xl font-bold mb-6 text-center">Tabla de Aplicaciones</h2>

        <button @click="$dispatch('open-modal', { id: 'modalAddAplicacion' })"
            class="mb-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Añadir Aplicación
        </button>

        <div x-show="loading" class="flex justify-center items-center mb-4">
            <span class="text-gray-600">Cargando datos...</span>
        </div>

        <div class="overflow-x-auto">
            <table id="tabla-aplicaciones" class="min-w-full border border-gray-300 rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border-b">ID</th>
                        <th class="px-4 py-2 border-b">Nombre</th>
                        <th class="px-4 py-2 border-b">Descripción</th>
                        <th class="px-4 py-2 border-b">Contrato</th>
                        <th class="px-4 py-2 border-b">Tipo</th>
                        <th class="px-4 py-2 border-b">Lanzamiento</th>
                        <th class="px-4 py-2 border-b">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Añadir -->
<div x-data="modalAddAplicacion" x-on:open-modal.window="if ($event.detail.id === 'modalAddAplicacion') openModal()"
    x-show="showAddAplicacion" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative">
        <button @click="showAddAplicacion = false"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✖️</button>

        <h2 class="text-2xl font-bold mb-6 text-center">Añadir Aplicación</h2>

        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" x-model="form.nombre" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.nombre"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                <input type="text" x-model="form.descripcion" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.descripcion"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Datos Contrato</label>
                <input type="text" x-model="form.datos_contrato" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.datos_contrato"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tipo Contrato</label>
                <input type="text" x-model="form.tipo_contrato" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.tipo_contrato"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Fecha Lanzamiento</label>
                <input type="date" x-model="form.fecha_lanzamiento" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.fecha_lanzamiento"></div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Estado</label>
                <input type="text" x-model="form.estado" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.estado"></div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
                    Guardar
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Scripts Alpine + DataTable -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('modalAplicaciones', () => ({
            showAplicaciones: false,
            dataTable: null,
            loading: false,

            init() {
                window.addEventListener('aplicacion-creada', () => {
                    this.dataTable?.ajax.reload();
                });
            },

            openModal() {
                this.showAplicaciones = true;
                this.$nextTick(() => this.initializeDataTable());
            },

            initializeDataTable() {
                this.loading = true;
                if (this.dataTable) {
                    this.dataTable.destroy();
                    $('#tabla-aplicaciones tbody').empty();
                }

                this.dataTable = $('#tabla-aplicaciones').DataTable({
                    ajax: {
                        url: '{{ route('aplicaciones.list') }}',
                        dataSrc: '',
                        complete: () => this.loading = false
                    },
                    columns: [
                        { data: 'aplicacion_id' },
                        { data: 'nombre' },
                        { data: 'descripcion' },
                        { data: 'datos_contrato' },
                        { data: 'tipo_contrato' },
                        { data: 'fecha_lanzamiento' },
                        { data: 'estado' },
                    ],
                    pageLength: 5,
                    responsive: true,
                    language: {
                        search: "Buscar:",
                        paginate: { next: "Siguiente", previous: "Anterior" },
                        info: "Mostrando _START_ a _END_ de _TOTAL_ registros"
                    }
                });
            }
        }));

        Alpine.data('modalAddAplicacion', () => ({
            showAddAplicacion: false,
            errors: {},
            form: {
                nombre: '',
                descripcion: '',
                datos_contrato: '',
                tipo_contrato: '',
                fecha_lanzamiento: '',
                estado: ''
            },

            resetForm() {
                this.form = {
                    nombre: '',
                    nif: '',
                    correo_electronico: '',
                    telefono: '',
                    direccion: '',
                    cooperativa_id: ''
                };
                this.errors = {};
            },

            openModal() {
                this.showAddAplicacion = true;
                this.resetForm();
            },

            submitForm() {
                $.ajax({
                    url: '{{ route('aplicaciones.store') }}',
                    method: 'POST',
                    data: this.form,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: () => {
                        this.showAddAplicacion = false;
                        this.form = {
                            nombre: '',
                            descripcion: '',
                            datos_contrato: '',
                            tipo_contrato: '',
                            fecha_lanzamiento: '',
                            estado: ''
                        };
                        window.dispatchEvent(new CustomEvent('aplicacion-creada'));
                        Swal.fire('Éxito', 'Aplicación creada correctamente', 'success');
                    },
                    error: (xhr) => {
                        if (xhr.status === 422) {
                            this.errors = xhr.responseJSON.errors;
                        } else {
                            Swal.fire('Error', 'Hubo un problema al guardar el cliente', 'error');
                            console.error(xhr.responseJSON);
                        }
                    }
                });
            }
        }));
    });
</script>