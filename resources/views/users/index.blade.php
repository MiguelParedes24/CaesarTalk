@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
    <div class="bg-gray-100 p-6 rounded-lg shadow-lg min-h-[70vh]">
        <h2 class="text-3xl font-bold text-blue-800 mb-4 text-center">Usuarios Disponibles</h2>
        <p class="text-lg text-center text-blue-600 mb-6">
            Conoce a otros miembros de la comunidad CaesarTalk y disfruta de una
            comunicación privada y segura.
        </p>
        <div class="mb-4 flex justify-center">
            <input type="text" id="searchUser" name="searchUser" placeholder="Buscar usuario..."
                class="bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg block w-1/2 p-2.5">
        </div>
        <div class="overflow-y-auto max-h-80">
            <table class="mx-auto w-full text-m text-center border-b text-gray-600 border-collapse ">
                <thead class="text-sm uppercase bg-blue-700 text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nombre</th>
                        <th scope="col" class="px-6 py-3">Apellido</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @forelse ($users as $user)
                        <input type="hidden" name="userId" value="{{ $user->id }}">
                        <tr class="bg-white border-b border-gray-300 hover:bg-gray-100">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $user->lastName }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 flex justify-center">
                                <button type="button" data-user-id="{{ $user->id }}"
                                    data-user-email="{{ $user->email }}" data-modal-toggle="send-userMessage-modal"
                                    data-modal-target="send-userMessage-modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 text-blue-700 hover:text-blue-900">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white border-b border-gray-200">
                            <td class="px-6 py-4 font-medium text-gray-900 text-center" colspan="4">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <script>

        </script>

        <!--Modal de Mensaje (desde User)-->
        <div id="send-userMessage-modal"
            class="modal hidden fixed inset-0 z-50 bg-gray-600 bg-opacity-50 justify-center items-center"
            data-modal-backdrop="static">
            <div class="modal-content rounded-lg bg-white p-6 shadow-lg w-full max-w-md">
                <div class="flex items-center justify-between border-b pb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Enviar Mensaje</h3>
                    <button type="button"
                        class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center"
                        data-modal-toggle="send-userMessage-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                </div>
                <div class="space-y-4 mt-4">
                    <form method="POST" action="{{ route('users.store') }}" id="userNewMessageForm">
                        @csrf
                        <input type="hidden" id="hidden-userSender-id" name="hidden_user_sender_id"
                            value="{{ auth()->id() }}">
                        <input type="hidden" id="hidden-user-id" name="hidden_user_id">
                        <div>
                            <label class="block text-md font-medium text-gray-900">Para:</label>
                            <input type="email" id="messageUser"
                                class="bg-gray-300 border border-gray-500 text-gray-900 text-md rounded-lg block w-full p-2.5 mb-2"
                                readonly>
                        </div>
                        <div>
                            <label class="block text-md font-medium text-gray-900" for="messageUserSubject">Asunto:</label>
                            <input type="text" id="messageUserSubject" name="subject"
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg block w-full p-2.5 mb-2"
                                placeholder="Inserta tu asunto aquí">
                        </div>
                        <div>
                            <label class="block text-md font-medium text-gray-900" for="messageUserBody">Mensaje:</label>
                            <textarea id="messageUserBody" name="body"
                                class="block p-2.5 w-full text-md text-gray-900 bg-gray-100 rounded-lg border border-gray-300 mb-2"
                                placeholder="Coloca tu mensaje aqui..."></textarea>
                        </div>
                        <div>
                            <label for="messageUserShift"
                                class="block text-md font-medium text-gray-900 mb-3">Desplazamiento</label>
                            <input type="number" name="shift" id="messageUserShift"
                                class="w-full bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5 mb-4"
                                placeholder="Número de desplazamientos" min="1">
                        </div>
                        <div>
                            <button id="sendUserMessage" type="submit"
                                class="bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg text-md px-5 py-2.5">Enviar
                                Mensaje</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--Fin Modal de Mensaje-->
        <!-- Modal de Confirmación -->
        <div id="confirmUserModal" class="fixed inset-0 z-50 bg-gray-600 bg-opacity-50 hidden justify-center items-center"
            data-modal-backdrop="static">
            <div class="rounded-lg bg-white p-6 shadow-lg w-full max-w-md">
                <h2 class="text-lg text-center font-semibold mb-4">¿Confirmar Envío?</h2>
                <input type="hidden" id="hidden-userReceiver-id" name="user_receiver_id">
                <p><strong>Destinatario:</strong> <span id="modalUserReceiver"></span></p>
                <p><strong>Número de desplazamientos:</strong> <span id="modalUserShift"></span></p>
                <p><strong>Asunto cifrado:</strong> <span id="modalUserSubject"></span></p>
                <p><strong>Mensaje cifrado:</strong> <span id="modalUserBody"></span></p>
                <div class="flex justify-end mt-4">
                    <button id="confirmUserMessage"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg mr-2 hover:bg-blue-500">Aceptar</button>
                    <button id="cancelUserMessage"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg  hover:bg-gray-500">Cancelar</button>
                </div>
            </div>
        </div>
        <!-- Fin de Modal de Confirmación -->
    </div>

    <script></script>
@endsection
