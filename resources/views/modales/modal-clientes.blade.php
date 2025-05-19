<div x-data="modalClientes" x-on:open-modal.window="if ($event.detail.id === 'modalClientes') openModal()"
    x-show="showClientes" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl overflow-y-auto max-h-[90vh] relative">
        <button @click="showClientes = false"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✖️</button>

        <h2 class="text-2xl font-bold mb-6 text-center">Tabla de Clientes</h2>

        <!-- Botón que dispara el modal -->
        <button @click="$dispatch('open-modal', { id: 'modalAddCliente' })"
            class="mb-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Añadir Cliente
        </button>

        <div x-show="loading" class="flex justify-center items-center mb-4">
            <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z">
                </path>
            </svg>
            <span class="ml-2 text-gray-600">Cargando datos...</span>
        </div>



        <div class="overflow-x-auto">
            <table id="tabla-clientes" class="min-w-full border border-gray-300 rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border-b">ID</th>
                        <th class="px-4 py-2 border-b">Nombre</th>
                        <th class="px-4 py-2 border-b">Email</th>
                        <th class="px-4 py-2 border-b">Teléfono</th>
                        <th class="px-4 py-2 border-b">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Añadir Cliente -->
<div x-data="modalAddCliente" x-on:open-modal.window="if ($event.detail.id === 'modalAddCliente') openModal()"
    x-show="showAddCliente" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;">

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative">
        <button @click="showAddCliente = false"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✖️</button>

        <h2 class="text-2xl font-bold mb-6 text-center">Añadir Cliente</h2>

        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" x-model="form.nombre" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.nombre"></div>

            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">NIF</label>
                <input type="text" x-model="form.nif" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.nif"></div>

            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" x-model="form.correo_electronico" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.correo_electronico"></div>

            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" x-model="form.telefono" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.telefono"></div>

            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                <input type="text" x-model="form.direccion" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.direccion"></div>

            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Cooperativa</label>
                <select x-model="form.cooperativa_id" class="w-full border rounded p-2">
                    <option value="">Seleccione una cooperativa</option>
                    <template x-for="coop in cooperativas" :key="coop . id">
                        <option :value="coop . id" x-text="coop.nombre"></option>
                    </template>
                </select>
                <div class="text-red-500 text-sm mt-1" x-text="errors.cooperativa_id"></div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
                    Guardar
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal Editar Cliente -->
<div x-data="modalEditCliente"
    x-on:open-modal.window="if ($event.detail.id === 'modalEditCliente') openModal($event.detail.clienteId)"
    x-show="showEditCliente" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative">
        <button @click="showEditCliente = false"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✖️</button>

        <h2 class="text-2xl font-bold mb-6 text-center">Editar Cliente</h2>

        <form @submit.prevent="submitEditForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" x-model="form.nombre" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.nombre"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">NIF</label>
                <input type="text" x-model="form.nif" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.nif"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" x-model="form.correo_electronico" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.correo_electronico"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" x-model="form.telefono" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.telefono"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                <input type="text" x-model="form.direccion" class="w-full border rounded p-2">
                <div class="text-red-500 text-sm mt-1" x-text="errors.direccion"></div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Cooperativa</label>
                <select x-model="form.cooperativa_id" class="w-full border rounded p-2">
                    <option value="">Seleccione una cooperativa</option>
                    <template x-for="coop in cooperativas" :key="coop . id">
                        <option :value="coop . id" x-text="coop.nombre"></option>
                    </template>
                </select>
                <div class="text-red-500 text-sm mt-1" x-text="errors.cooperativa_id"></div>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" @click="showEditCliente = false"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>



<script>

    document.addEventListener('alpine:init', () => {
        Alpine.data('modalClientes', () => ({
            showClientes: false,
            dataTable: null,
            loading: false,

            openModal() {
                this.showClientes = true;
                this.$nextTick(() => {
                    this.initializeDataTable();
                });
            },

            init() {
                window.addEventListener('cliente-creado', () => {
                    if (this.dataTable) {
                        this.dataTable.ajax.reload();
                    }
                });

                window.addEventListener('cliente-actualizado', () => {
                    if (this.dataTable) {
                        this.dataTable.ajax.reload();
                    }
                });
            },

            initializeDataTable() {
                this.loading = true;

                if (this.dataTable) {
                    this.dataTable.destroy();
                    $('#tabla-clientes tbody').empty();
                }

                this.dataTable = $('#tabla-clientes').DataTable({
                    ajax: {
                        url: '{{ route('clientes.list') }}',
                        dataSrc: '',
                        complete: () => {
                            this.loading = false;
                        }
                    },
                    columns: [
                        { data: "cliente_id" },
                        { data: "nombre" },
                        { data: "correo_electronico" },
                        { data: "telefono" },
                        {
                            data: "cliente_id",
                            render: (data, type, row) => {
                                return `
                                <button 
                                    class="bg-blue-500 text-white px-2 py-1 rounded mr-2" 
                                    @click="$dispatch('open-modal', { id: 'modalEditCliente', clienteId: ${data} })">
                                    Editar
                                </button>`;
                            }
                        }

                    ],
                    pageLength: 5,
                    lengthChange: true,
                    responsive: true,
                    language: {
                        search: "Buscar:",
                        paginate: {
                            next: "Siguiente",
                            previous: "Anterior"
                        },
                        info: "Mostrando _START_ a _END_ de _TOTAL_ registros"
                    }
                });
            },

            deleteCliente(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/clientes/${id}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: () => {
                                this.dataTable.ajax.reload();
                                Swal.fire(
                                    'Eliminado!',
                                    'El cliente ha sido eliminado.',
                                    'success'
                                );
                            },
                            error: () => {
                                Swal.fire(
                                    'Error!',
                                    'Hubo un problema al eliminar el cliente.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }
        }));

        Alpine.data('modalEditCliente', () => ({
            showEditCliente: false,
            cooperativas: [],
            errors: {},
            form: {
                cliente_id: '',
                nombre: '',
                nif: '',
                correo_electronico: '',
                telefono: '',
                direccion: '',
                cooperativa_id: ''
            },

            async fetchCliente(clienteId) {
                try {
                    const res = await axios.get(`/clientes/${clienteId}`);
                    this.form = res.data;
                } catch (err) {
                    console.error("Error al cargar los datos del cliente:", err);
                }
            },

            async fetchCooperativas() {
                try {
                    const res = await axios.get('{{ route('cooperativas.list') }}');
                    this.cooperativas = res.data;
                } catch (err) {
                    console.error("Error al cargar cooperativas:", err);
                }
            },

            openModal(clienteId) {
                this.fetchCliente(clienteId);  // Cargar los datos del cliente desde el backend
                this.fetchCooperativas();      // Cargar las cooperativas disponibles
                this.showEditCliente = true;
            },

            submitEditForm() {
                axios.put(`/clientes/${this.form.cliente_id}`, this.form)
                    .then(response => {
                        this.showEditCliente = false;
                        window.dispatchEvent(new CustomEvent('cliente-actualizado'));
                        Swal.fire('Éxito', 'Cliente actualizado correctamente', 'success');
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        } else {
                            Swal.fire('Error', 'Hubo un problema al actualizar el cliente', 'error');
                        }
                    });
            }
        }));



        Alpine.data('modalAddCliente', () => ({
            showAddCliente: false,
            cooperativas: [],
            errors: {},


            form: {
                nombre: '',
                nif: '',
                correo_electronico: '',
                telefono: '',
                direccion: '',
                cooperativa_id: ''
            },

            async fetchCooperativas() {
                try {
                    const res = await axios.get('{{ route('cooperativas.list') }}');
                    this.cooperativas = res.data;
                } catch (err) {
                    console.error("Error al cargar cooperativas:", err);
                }
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
                this.resetForm(); // Resetear el formulario al abrir el modal
                this.showAddCliente = true;
                this.fetchCooperativas();
            },



            submitForm() {
                $.ajax({
                    url: '{{ route('clientes.store') }}',
                    method: 'POST',
                    data: this.form,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: (response) => {
                        this.showAddCliente = false;
                        this.form = {
                            nombre: '',
                            nif: '',
                            correo_electronico: '',
                            telefono: '',
                            direccion: '',
                            cooperativa_id: ''
                        };
                        window.dispatchEvent(new CustomEvent('cliente-creado'));
                        this.errors = {};
                        Swal.fire('Éxito', 'Cliente añadido correctamente', 'success');
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

<style>
    /* Añadir espacio alrededor de los controles de DataTables */
    .dataTables_wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    /* Ajustes extra opcionales */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }

    .dataTables_length select {
        padding: 0.25rem 0.5rem;
        border: 1px solid #d1d5db;
        /* gray-300 */
        border-radius: 0.375rem;
        /* rounded-md */
        font-size: 0.875rem;
        /* text-sm */
        background-color: white;
        min-width: 4rem;
        /* 👈 fuerza el ancho mínimo del select */
    }
</style>