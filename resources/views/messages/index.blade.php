@extends('layouts.app')
@section('title', 'Mensajes')
@section('content')
    <div>
        <div class="bg-white p-6 rounded-lg shadow-lg min-h-[80vh]">
            <h2 class="text-3xl font-bold text-blue-800 mb-4 text-center">Mis Mensajes</h2>
            <p class="text-lg text-center text-blue-600 mb-6 ">Gestiona tus conversaciones de forma fácil y segura.</p>
            <!-- Modal -->
            <button id="newMessageBtn" data-modal-target="new-message-modal" data-modal-toggle="new-message-modal"
                data-new-message="true"
                class="block text-white  bg-blue-700 hover:bg-blue-600 font-medium rounded-lg text-md p-3 text-center">
                <p class="flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14m-7 7V5" />
                    </svg>
                    Nuevo Mensaje
                </p>
            </button>
            <br><br>

            <!-- Modal para Nuevo Mensaje -->
            <div id="new-message-modal" tabindex="-1" aria-hidden="true" data-modal-backdrop="static"
                class="modal hidden fixed inset-0 z-50 justify-center items-center bg-gray-600 bg-opacity-50">
                <div class="modal-content bg-white rounded-lg shadow-lg w-full max-w-md p-4">
                    <!-- Modal content -->

                    <!-- Modal header -->
                    <div class="flex items-center justify-between border-b pb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Nuevo Mensaje
                        </h3>
                        <button type="button"
                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center"
                            data-modal-toggle="new-message-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Cerrar</span>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="space-y-4 mt-4">
                        <form method="POST" action="{{ route('messages.store') }}" id="newMessageForm">
                            @csrf
                            <input type="hidden" name="senderId" id="hidden-sender-id" value="{{ auth()->id() }}">
                            <!-- Receiver -->
                            <div>
                                <label for="newMessageReceiver"
                                    class="block text-md font-medium text-gray-900 mb-2">Para</label>
                                <input type="text" name="receiver" id="newMessageReceiver"
                                    class="bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg block w-full p-2.5 mb-3"
                                    placeholder="Destinatario">
                                <div id="recommendations" class="absolute bg-white w-[50vh] shadow-md z-10 hidden">
                                </div>
                            </div>
                            <!-- Subject -->
                            <div>
                                <label for="newMessageSubject"
                                    class="block mb-2 text-md font-medium text-gray-900 ">Asunto</label>
                                <input type="text" name="subject" id="newMessageSubject"
                                    class="bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg block w-full p-2.5 mb-3"
                                    placeholder="Coloca tu Asunto aquí">
                            </div>
                            <!-- Body Message -->
                            <div>
                                <label for="newMessageBody"
                                    class="block mb-2 text-md font-medium text-gray-900">Mensaje</label>
                                <div class="relative">
                                    <textarea id="newMessageBody" rows="4"
                                        class="block p-2.5 pb-10 w-full text-md text-gray-900 bg-gray-100 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 mb-2"
                                        placeholder="Escribe tu mensaje aquí..." maxlength="1000"></textarea>
                                    <div id="contadorNewMessageBody mt-1"
                                        class="absolute bottom-1 right-5 text-xs text-gray-500">
                                        1000
                                        /1000
                                    </div>
                                </div>
                            </div>
                            <!-- Shift -->
                            <div>
                                <label for="newMessageShift"
                                    class=" block text-md font-medium text-gray-900 mb-3">Desplazamiento</label>
                                <input type="number" name="shift" id="newMessageShift"
                                    class="w-full bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5 mb-4"
                                    placeholder="Número de desplazamientos" min="1" max="9">
                            </div>
                            <br>
                            <!-- Buttons -->
                            <div class="flex justify-end space-x-4">
                                <button type="reset"
                                    class="text-white bg-gray-500 hover:bg-gray-600 font-medium rounded-lg text-md px-5 py-2.5">
                                    Limpiar
                                </button>
                                <button type="submit" id="sendNewMessage"
                                    class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-md px-5 py-2.5">
                                    Enviar
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Fin del Modal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 h-full">
                <!-- Mensajes Recibidos -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-8 h-8 mx-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Mensajes Recibidos
                    </h3>
                    <div class="max-h-80 overflow-y-auto border border-gray-400 p-2 rounded-lg">
                        <ul>
                            @forelse ($receivedMessages as $message)
                                <li class="flex justify-between border-gray-600 rounded-lg mb-2 shadow border hover:cursor-pointer {{ $message->is_read ? 'bg-gray-300' : 'bg-gray-100' }} "
                                    id="{{ $message->id }}">
                                    <div class="px-2 py-1 mb-2 messageCard">
                                        <strong>De:</strong> {{ $message->sender->name }}
                                        {{ $message->sender->lastName }}
                                        <br>
                                        <strong>Recibido:</strong>
                                        @if ($message->received_at)
                                            {{ $message->received_at->format('d/m/Y H:i') }}
                                        @else
                                            Pendiente
                                        @endif
                                        @if ($message->is_read)
                                            <span class="ml-4 text-md text-green-600">(Leído)</span>
                                        @endif
                                    </div>

                                    <button data-modal-target="view-message-modal" data-modal-toggle="view-message-modal"
                                        data-message-id="{{ $message->id }}"
                                        data-sender="{{ $message->sender->name }} {{ $message->sender->lastName }}"
                                        data-subject="{{ $message->subject }}" data-body="{{ $message->body }}"
                                        class=" text-white bg-gray-600 hover:bg-blue-500  font-medium rounded-r-lg text-m px-2 text-center shadow transition duration-300 ease-in-out view-message-btn">
                                        Ver mensaje
                                    </button>
                                </li>
                            @empty
                                <p class="text-gray-500">No has recibido mensajes aún.</p>
                            @endforelse
                            <!-- Modal de Visualización de Mensaje Recibido -->
                            <div id="view-message-modal" tabindex="-1" aria-hidden="true" data-modal-backdrop="static"
                                class=" modal hidden fixed inset-0 z-50  justify-center items-center bg-gray-600 bg-opacity-50">
                                <div class="modal-content bg-white rounded-lg shadow-lg w-full max-w-md p-6">

                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between border-b pb-4">
                                        <svg class="w-8 h-8 text-gray-800" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2m-8 1V4m0 12-4-4m4 4 4-4" />
                                        </svg>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Mensaje Recibido
                                        </h3>
                                        <button type="button"
                                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center"
                                            data-modal-toggle="view-message-modal">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Cerrar modal</span>
                                        </button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="space-y-4 mt-4">
                                        <!-- Sender -->
                                        <div>
                                            <label for="messageSender"
                                                class="block text-sm font-medium text-gray-900 mb-1">De</label>
                                            <input type="text" id="messageSender" disabled
                                                class="bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg block w-full p-2.5 mb-1">
                                            <input type="hidden" id="hidden-sender-id" name="hidden_sender_id">
                                            <input type="hidden" name="sender_email" id="hidden-sender-email">
                                        </div>

                                        <!-- Subject -->
                                        <div>
                                            <label for="messageSubject"
                                                class="block text-sm font-medium text-gray-900 mb-1">Asunto</label>
                                            <input type="text" id="messageSubject" disabled
                                                class="bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg block w-full p-2.5 mb-1">
                                        </div>

                                        <!-- Body Message -->
                                        <div>
                                            <label for="messageBody"
                                                class="block text-sm font-medium text-gray-900 mb-1">Mensaje</label>
                                            <textarea id="messageBody" rows="4" disabled
                                                class="block p-2.5 w-full text-md text-gray-900 bg-gray-100 rounded-lg border border-gray-300"></textarea>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex justify-end mt-4">
                                        <button type="button" id="replyMessageBtn"
                                            data-modal-target="reply-message-modal"
                                            data-modal-toggle="reply-message-modal" data-receiver-id=""
                                            data-receiver-email=""
                                            class="bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg text-md px-5 py-2.5">
                                            Responder Mensaje
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin del Modal de Visualización de Mensajes -->

                            <!-- Modal para Responder -->
                            <div id="reply-message-modal"
                                class="modal hidden fixed inset-0 z-50 bg-gray-600 bg-opacity-50 justify-center items-center"
                                data-modal-backdrop="static">
                                <div class="modal-content rounded-lg bg-white p-6 shadow-lg w-full max-w-md">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between border-b pb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Responder Mensaje</h3>
                                        <button type="button"
                                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center"
                                            data-modal-toggle="reply-message-modal">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Cerrar modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="space-y-4 mt-4">
                                        <!--Receiver-->
                                        <form method="POST" action="{{ route('messages.store') }}"
                                            id="replyMessageForm">
                                            @csrf
                                            <div>
                                                <input type="hidden" id="hidden-replyreceiver-id"
                                                    name="hidden_receiver_id">
                                                <label class="block text-md font-medium text-gray-900 mb-1">Para:</label>
                                                <input type="email" id="replyMessageReceiver"
                                                    class="bg-gray-100 border border-gray-500 text-gray-900 text-md rounded-lg block w-full p-2.5 mb-1"
                                                    readonly>
                                            </div>
                                            <!--Subject-->
                                            <div>
                                                <label class="block text-md font-medium text-gray-900 mb-1">Asunto:</label>
                                                <input type="text" id="replyMessageSubject"
                                                    class="bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg block w-full p-2.5 mb-1">
                                            </div>
                                            <!--Body Message-->
                                            <div>
                                                <label
                                                    class="block text-md font-medium text-gray-900 mb-1">Mensaje:</label>
                                                <div class="relative">
                                                    <textarea id="replyMessageBody"
                                                        class="block p-2.5 pb-10 w-full text-md text-gray-900 bg-gray-100 rounded-lg border border-gray-300 mb-1"
                                                        maxlength="1000"></textarea>
                                                    <div id="contadorReplyMessageBody"
                                                        class="absolute bottom-1 right-5 text-xs text-gray-500">1000/1000
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- shift -->
                                            <div>
                                                <label for="replyMessageShift"
                                                    class=" block text-md font-medium text-gray-900 mb-3">Desplazamiento</label>
                                                <input type="number" name="shift" id="replyMessageShift"
                                                    class="w-full bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5 mb-4"
                                                    placeholder="Número de desplazamientos" min="1"
                                                    max="9">
                                            </div>
                                            <!--Button-->
                                            <div class="flex justify-end mt-4">
                                                <button id="sendReplyMessage" type="submit"
                                                    class="bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg text-md px-5 py-2.5">Enviar
                                                    Mensaje
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de Confirmación -->
                            <div id="confirmModal"
                                class="fixed inset-0 z-50 bg-gray-600 bg-opacity-50 hidden justify-center items-center"
                                data-modal-backdrop="static">
                                <div class="rounded-lg bg-white p-6 shadow-lg w-full max-w-md">
                                    <h2 class="text-lg text-center font-semibold mb-4">¿Confirmar Envío?</h2>

                                    <input type="hidden" id="hidden-receiver-id" name="receiver_id">
                                    <p><strong>Destinatario:</strong> <span id="modalReceiver"></span></p>
                                    <p><strong>Número de desplazamientos:</strong> <span id="modalShift"></span></p>
                                    <p><strong>Asunto cifrado:</strong> <span id="modalSubject"></span></p>
                                    <p><strong>Mensaje cifrado:</strong> <span id="modalBody"></span></p>
                                    <div class="flex justify-end mt-4">
                                        <button id="confirmMessage"
                                            class="bg-blue-600 text-white px-4 py-2 rounded-lg mr-2 hover:bg-blue-500">Aceptar</button>
                                        <button id="cancelMessage"
                                            class="bg-gray-600 text-white px-4 py-2 rounded-lg  hover:bg-gray-500">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin de Modal de Confirmación -->
                        </ul>
                    </div>
                </div>

                <!-- Mensajes Enviados -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-8 h-8 mx-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Mensajes Enviados
                    </h3>
                    <div class="max-h-80 overflow-y-auto border border-gray-400 p-2 rounded-lg">
                        <ul>
                            @forelse ($sentMessages as $message)
                                <li class="flex justify-between border-gray-600 rounded-lg mb-2 bg-gray-100  border hover:cursor-pointer hover:bg-gray-200 shadow transition duration-300 ease-in-out"
                                    id="{{ $message->id }}">
                                    <div class="px-2 py-1 mb-2">
                                        <strong>Para:</strong> {{ $message->receiver->name }}
                                        {{ $message->receiver->lastName }} <br>
                                        <strong>Enviado:</strong> {{ $message->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    <button data-modal-target="view-sentMessage-modal"
                                        data-modal-toggle="view-sentMessage-modal"
                                        data-sentMessage-id="{{ $message->id }}"
                                        data-sender="{{ $message->sender->name }} {{ $message->sender->lastName }}"
                                        data-subject="{{ $message->subject }}" data-body="{{ $message->body }}"
                                        class=" text-white bg-gray-600 hover:bg-blue-500  font-medium rounded-r-lg text-md px-2 text-center shadow transition duration-300 ease-in-out view-sentMessage-btn">
                                        Ver mensaje
                                    </button>

                                </li>

                            @empty
                                <p class="text-gray-500">No has enviado mensajes aún.</p>
                            @endforelse

                            <!-- Modal de Visualización de Mensaje Enviado -->
                            <div id="view-sentMessage-modal" tabindex="-1" aria-hidden="true"
                                data-modal-backdrop="static"
                                class="modal hidden fixed inset-0 z-50  justify-center items-center bg-gray-600 bg-opacity-50">
                                <div class="modal-content rounded-lg bg-white p-6 shadow-lg w-full max-w-md">
                                    <!-- Modal content -->

                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between border-b pb-4">
                                        <svg class="w-8 h-8 text-gray-800" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2M12 4v12m0-12 4 4m-4-4L8 8" />
                                        </svg>

                                        <h3 class="text-lg font-semibold mb-4">
                                            Mensaje Enviado
                                        </h3>
                                        <button type="button"
                                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center"
                                            data-modal-toggle="view-sentMessage-modal">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Cerrar modal</span>
                                        </button>
                                    </div>

                                    <!-- Modal body -->

                                    <div class="space-y-4 mt-4">
                                        <!-- Remitente -->
                                        <div>
                                            <label for = "messageSentReceiver"
                                                class="block mb-2 text-md font-medium text-gray-900">Para</label>
                                            <input type="text" id="messageSentReceiver" disabled
                                                class="bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg block w-full p-2.5">
                                        </div>
                                        <!-- Asunto -->
                                        <div>
                                            <label for="messageSentSubject"
                                                class="block mb-2 text-md font-medium text-gray-900">Asunto</label>
                                            <input type="text" id="messageSentSubject" disabled
                                                class="bg-gray-100 border border-gray-300 text-gray-900 text-md rounded-lg block w-full p-2.5">
                                        </div>
                                        <!-- Cuerpo del mensaje -->
                                        <div>
                                            <label for="messageSentBody"
                                                class="block mb-2 text-md font-medium text-gray-900">Mensaje</label>
                                            <textarea id="messageSentBody" rows="4" disabled
                                                class="block p-2.5 w-full text-md text-gray-900 bg-gray-100 rounded-lg border border-gray-300"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fin del Modal de Visualización de Mensajes -->
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
