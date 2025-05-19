<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-8 lg:px-20">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg p-8">


                @if (session('success'))
                    <div id="flash-success"
                         class="mb-4 px-4 py-3 rounded bg-green-100 border border-green-400 text-green-700 text-sm font-semibold">
                        {{ session('success') }}
                    </div>
                    <script>
                        setTimeout(() => {
                            document.getElementById('flash-success')?.remove();
                        }, 4000);
                    </script>
                @endif


                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">Clientes</h3>
                    <button onclick="openAddModalBtn()"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow">
                        Nuevo Cliente
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table id="clientesTable" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow">
                        <thead class="bg-blue-600 dark:bg-blue-900 text-white uppercase text-sm leading-normal">
                        <tr>
                            <th class="py-3 px-6 text-left">Nombre Cliente</th>
                            <th class="py-3 px-6 text-left">Email</th>
                            <th class="py-3 px-6 text-left">Teléfono</th>
                            <th class="py-3 px-6 text-left">Cooperativa</th>
                            <th class="py-3 px-6 text-left">Aplicaciones</th> <th class="py-3 px-6 text-center">Acciones</th> </tr>
                        </thead>
                        <tbody class="text-gray-800 dark:text-gray-300 text-sm font-light">
                        @foreach ($clientes as $cliente)
                            <tr
                                class="border-b border-gray-300 dark:border-gray-600 odd:bg-gray-100 dark:odd:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700">
                                <td class="py-4 px-6">{{ $cliente->nombre }}</td>
                                <td class="py-4 px-6">{{ $cliente->correo_electronico }}</td>
                                <td class="py-4 px-6">{{ $cliente->telefono }}</td>
                                <td class="py-4 px-6">{{ $cliente->cooperativa->nombre }}</td>
                                <td class="py-4 px-6">
                                    {{ $cliente->aplicaciones->pluck('nombre')->implode(', ') ?: 'No tiene aplicaciones asociadas '}}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <button onclick="openModal({{ $cliente->cliente_id }})"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Ver Aplicaciones
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- <div class="mt-4">
                    {{ $clientes->links() }}
                </div> --}}

                @if ($clientes->isEmpty())
                    <div class="text-center text-gray-500 dark:text-gray-400 py-6">
                        No hay clientes registrados.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="modalAddCliente" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg max-w-3xl w-full overflow-y-auto max-h-[90vh]">
            <h2 class="text-2xl font-semibold mb-4">Añadir Nuevo Cliente</h2>

            <form id="formAddCliente" action="{{ route('clientes_aplicacion.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700">Cliente:</label>
                    <select name="cliente_id" class="w-full border rounded p-2 mt-1">
                        <option value="">-- Seleccionar Cliente --</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->cliente_id }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                    <div class="text-red-500 text-sm mt-1">
                        {{ session('errors')?->first('cliente_id') }}
                    </div>
                </div>


                <div id="aplicacionesContainer">
                    <div class="aplicacion-group border-t pt-4 mt-4">

                        <div class="mb-4">
                            <label class="block text-gray-700">Aplicación:</label>
                            <select name="aplicaciones[0][aplicacion_id]" class="w-full border rounded p-2 mt-1">
                                <option value="">-- Seleccionar --</option>
                                @foreach ($aplicaciones as $aplicacion)
                                    <option value="{{ $aplicacion->aplicacion_id }}" {{ old('aplicaciones.0.aplicacion_id') == $aplicacion->aplicacion_id ? 'selected' : '' }}>
                                        {{ $aplicacion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-red-500 text-sm mt-1">
                                {{ session('errors')?->first('aplicaciones.0.aplicacion_id') }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700">Tipo de Contrato:</label>
                            <input type="text" name="aplicaciones[0][tipo]" class="w-full border rounded p-2 mt-1" placeholder="Mensual, Anual, etc."
                                   value="{{ old('aplicaciones.0.tipo') }}">
                            <div class="text-red-500 text-sm mt-1">
                                {{ session('errors')?->first('aplicaciones.0.tipo') }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700">Versión:</label>
                            <input type="text" name="aplicaciones[0][version]" class="w-full border rounded p-2 mt-1"  placeholder="v1.0, v2.1, etc."
                                   value="{{ old('aplicaciones.0.version') }}">
                            <div class="text-red-500 text-sm mt-1">
                                {{ session('errors')?->first('aplicaciones.0.version') }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700">Fecha de Contratación:</label>
                            <input type="date" name="aplicaciones[0][fecha_contratacion]"
                                   class="w-full border rounded p-2 mt-1"
                                   value="{{ old('aplicaciones.0.fecha_contratacion') }}">
                            <div class="text-red-500 text-sm mt-1">
                                {{ session('errors')?->first('aplicaciones.0.fecha_contratacion') }}
                            </div>
                        </div>

                    </div>
                </div>

                <div class="my-6">
                    <button type="button" id="btnAgregarAplicacion"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Agregar Otra Aplicación
                    </button>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" onclick="closeAddModal()"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                        Guardar
                    </button>
                </div>
            </form>

        </div>
    </div>


    <div id="modalAplicaciones"
         class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg max-w-lg w-full">
            <h2 class="text-xl font-semibold mb-4">Aplicaciones del Cliente</h2>
            <div id="modalContent">
                </div>
            <div class="mt-4 text-right">
                <button onclick="closeModal()"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
     

        <script>
            $(document).ready(function () {
                $('#clientesTable').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                    }
                });
            });

            const aplicaciones = @json($clientes->keyBy('cliente_id'));

            function openModal(clienteId) {
                const cliente = aplicaciones[clienteId];
                let html = '';

                if (cliente && cliente.aplicaciones.length > 0) {
                    html += '<ul class="list-disc pl-5">';
                    cliente.aplicaciones.forEach(aplicacion => {
                        html += `<li><strong>${aplicacion.nombre}</strong> - ${aplicacion.pivot.tipo} - Versión: ${aplicacion.pivot.version}</li>`;
                    });
                    html += '</ul>';
                } else {
                    html = '<p>Este cliente no tiene aplicaciones registradas.</p>';
                }

                document.getElementById('modalContent').innerHTML = html;
                document.getElementById('modalAplicaciones').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('modalAplicaciones').classList.add('hidden');
            }

            function openAddModalBtn() {
                document.getElementById('modalAddCliente').classList.remove('hidden');
            }

            function closeAddModal() {
                document.getElementById('modalAddCliente').classList.add('hidden');
                
            }

            let aplicacionIndex = 1; // Empieza en 1 porque el 0 ya existe

            document.getElementById('btnAgregarAplicacion').addEventListener('click', function () {
                const container = document.getElementById('aplicacionesContainer');

                const nuevoGrupo = document.createElement('div');
                nuevoGrupo.classList.add('aplicacion-group', 'border-t', 'pt-4', 'mt-4');

                nuevoGrupo.innerHTML = `
                                                                        <div class="mb-4">
                                                                            <label class="block text-gray-700">Aplicación:</label>
                                                                            <select name="aplicaciones[${aplicacionIndex}][aplicacion_id]" class="w-full border rounded p-2 mt-1">
                                                                                <option value="">-- Seleccionar --</option>
                                                                                @foreach ($aplicaciones as $aplicacion)
                                                                                    <option value="{{ $aplicacion->aplicacion_id }}">{{ $aplicacion->nombre }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label class="block text-gray-700">Tipo de Contrato:</label>
                                                                            <input type="text" name="aplicaciones[${aplicacionIndex}][tipo]" class="w-full border rounded p-2 mt-1" placeholder="Mensual, Anual, etc.">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label class="block text-gray-700">Versión:</label>
                                                                            <input type="text" name="aplicaciones[${aplicacionIndex}][version]" class="w-full border rounded p-2 mt-1" placeholder="v1.0, v2.1, etc.">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label class="block text-gray-700">Fecha de Contratación:</label>
                                                                            <input type="date" name="aplicaciones[${aplicacionIndex}][fecha_contratacion]" class="w-full border rounded p-2 mt-1">
                                                                        </div>

                                                                        <div class="mb-4 flex justify-end">
                                                                    <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-medium py-1 px-2 rounded btn-remove-aplicacion flex items-center gap-1 text-sm">
                                                                        <i class="fas fa-trash text-base"></i>
                                                                        <span>Eliminar</span>
                                                                    </button>
                                                                </div>
                                                                        `;

                container.appendChild(nuevoGrupo);
                aplicacionIndex++;

                nuevoGrupo.querySelector('.btn-remove-aplicacion').addEventListener('click', function () {
                    nuevoGrupo.remove();
                    // Opcional: en vez de ocultar puedes hacer container.removeChild(nuevoGrupo);
                });
            });


        </script>
    @endpush

    @if ($errors->any())
        @push('scripts')
            <script>
                window.addEventListener('load', function () {
                    document.getElementById('modalAddCliente').classList.remove('hidden');
                });
            </script>
        @endpush
    @endif

    @if (session('success'))
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            </script>
        @endpush
    @endif


</x-app-layout>