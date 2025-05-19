<div x-data="modalCooperativas" x-on:open-modal.window="if ($event.detail.id === 'modalCooperativas') openModal()"
    x-show="showCooperativas" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-5xl overflow-y-auto max-h-[90vh] relative">
        <button @click="showCooperativas = false"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✖️</button>

        <h2 class="text-2xl font-bold mb-6 text-center">Tabla de Cooperativas</h2>

        <!-- Botón que dispara el modal -->
        <button @click="$dispatch('open-modal', { id: 'modalAddCooperativa' })"
            class="mb-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Añadir Cooperativa
        </button>

        <div x-show="loading" class="flex justify-center items-center mb-4">
            <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
            </svg>
            <span class="ml-2 text-gray-600">Cargando datos...</span>
        </div>

        <div class="overflow-x-auto">
            <table id="tabla-cooperativas" class="min-w-full border border-gray-300 rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border-b">ID</th>
                        <th class="px-4 py-2 border-b">Nombre</th>
                        <th class="px-4 py-2 border-b">Dirección</th>
                        <th class="px-4 py-2 border-b">Teléfono</th>
                        <th class="px-4 py-2 border-b">Email</th>
                        <th class="px-4 py-2 border-b">Fecha Fundación</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <!-- Datos vía AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Añadir Cooperativa -->
<div x-data="modalAddCooperativa"
    x-on:open-modal.window="if ($event.detail.id === 'modalAddCooperativa') openModal()"
    x-show="showAddCooperativa" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative">
        <button @click="showAddCooperativa = false"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✖️</button>

        <h2 class="text-2xl font-bold mb-6 text-center">Añadir Cooperativa</h2>

        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" x-model="form.nombre" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.estado"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                <input type="text" x-model="form.direccion" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.direccion"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" x-model="form.telefono" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.telefono"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" x-model="form.correo_electronico" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.correo_electronico"></div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Fecha de Fundación</label>
                <input type="date" x-model="form.fecha_fundacion" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.fecha_fundacion"></div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('modalCooperativas', () => ({
            showCooperativas: false,
            dataTable: null,
            loading: false,

            init() {
                window.addEventListener('cooperativa-creada', () => {
                    if (this.dataTable) this.dataTable.ajax.reload();
                });
            },

            openModal() {
                this.showCooperativas = true;
                this.$nextTick(() => this.initializeDataTable());
            },

            initializeDataTable() {
                this.loading = true;
                if (this.dataTable) {
                    this.dataTable.destroy();
                    $('#tabla-cooperativas tbody').empty();
                }

                this.dataTable = $('#tabla-cooperativas').DataTable({
                    ajax: {
                        url: '{{ route('cooperativas.list') }}',
                        dataSrc: '',
                        complete: () => this.loading = false
                    },
                    columns: [
                        { data: 'id' },
                        { data: 'nombre' },
                        { data: 'direccion' },
                        { data: 'telefono' },
                        { data: 'correo_electronico' },
                        { data: 'fecha_fundacion' }
                    ],
                    pageLength: 5,
                    lengthChange: true,
                    responsive: true,
                    language: {
                        search: "Buscar:",
                        paginate: { next: "Siguiente", previous: "Anterior" },
                        info: "Mostrando _START_ a _END_ de _TOTAL_ registros"
                    }
                });
            }
        }));

        Alpine.data('modalAddCooperativa', () => ({
            showAddCooperativa: false,
            errors: {},
            form: {
                nombre: '',
                direccion: '',
                telefono: '',
                correo_electronico: '',
                fecha_fundacion: ''
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
                this.showAddCooperativa = true;
            },

            submitForm() {
                $.ajax({
                    url: '{{ route('cooperativas.store') }}',
                    method: 'POST',
                    data: this.form,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: () => {
                        this.showAddCooperativa = false;
                        this.form = {
                            nombre: '', direccion: '', telefono: '',
                            correo_electronico: '', fecha_fundacion: ''
                        };
                        window.dispatchEvent(new CustomEvent('cooperativa-creada'));
                        Swal.fire('Éxito', 'Cooperativa guardada correctamente', 'success');
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

