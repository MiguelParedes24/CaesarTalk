@extends('layouts.app')
@section('title', 'Registro')
@section('content')
    <div class="flex flex-col md:flex-row justify-center min-h-[80vh] mt-[-2rem]">
        <div class="md:w-1/2 bg-white p-6 rounded-l-lg shadow-lg w-full max-w-md max-h-[85vh] overflow-y-auto">
            <h2 class="text-2xl font-semibold text-center text-blue-600 mb-2">Crea una cuenta</h2>
            <p class="text-gray-600 text-center text-sm">
                Completa los siguientes datos para registrarse en CaesarTalk.
            </p>

            <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-3"
                onsubmit="validateRegister(event, this);">
                @csrf
                <div class="mb-2">
                    <label for="name" class="block text-gray-700 font-medium mb-1 text-sm">Nombre</label>
                    <input type="text" name="name" id="name"
                        class="formField w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        placeholder="Ingrese su nombre" value="{{ old('name') }}">

                    <span class="hidden peer-invalid:block text-red-500 text-sm mt-1">
                        Este campo es obligatorio.
                    </span>

                </div>

                <div class="mb-2">
                    <label for="lastName" class="block text-gray-700 font-medium mb-1 text-sm">Apellido</label>
                    <input type="text" name="lastName" id="lastName"
                        class="formField w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        placeholder="Ingrese su apellido" value="{{ old('lastName') }}">

                    <span class="hidden peer-invalid:block text-red-500 text-sm mt-1">
                        Este campo es obligatorio.
                    </span>

                </div>

                <div class="mb-2">
                    <label for="email" class="block text-gray-700 font-medium mb-1 text-sm">Correo Electrónico</label>
                    <input type="email" name="email" id="email"
                        class="formField w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        placeholder="Ingrese su correo" value="{{ old('email') }}">

                    <span class="hidden peer-invalid:block text-red-500 text-sm mt-1">
                        Este campo es obligatorio.
                    </span>
                    <span id="error-email" class="text-red-500 text-sm mt-1 hidden"></span>
                    @if ($errors->has('email'))
                        <span style="color: red;">{{ $errors->first('email') }}</span>
                    @endif

                </div>

                <div class="mb-2">
                    <label for="userName" class="block text-gray-700 font-medium mb-1 text-sm">Nombre de Usuario</label>
                    <input type="text" name="userName" id="userName"
                        class="formField w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        placeholder="Ingrese su nombre de usuario" value="{{ old('userName') }}">
                    <span class="hidden peer-invalid:block text-red-500 text-sm mt-1">
                        Este campo es obligatorio.
                    </span>
                    <span id="error-userName" class="text-red-500 text-sm mt-1 hidden"></span>
                    @if ($errors->has('userName'))
                        <span style="color: red;">{{ $errors->first('userName') }}</span>
                    @endif
                </div>

                <div class="mb-2">
                    <label for="password" class="block text-gray-700 font-medium mb-1 text-sm">Contraseña</label>
                    <input type="password" name="password" id="password"
                        class="formField w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        placeholder="Ingrese su contraseña">

                    <span class="hidden peer-invalid:block text-red-500 text-sm mt-1">
                        Este campo es obligatorio.
                    </span>

                </div>

                <div class="mb-2">
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-1 text-sm">Confirmar
                        Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="formField w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        placeholder="Ingrese su contraseña nuevamente">
                    <span class="hidden peer-invalid:block text-red-500 text-sm mt-1">
                        Este campo es obligatorio.
                    </span>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200 text-sm">
                    Registrarse
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}"
                    class="text-blue-600 hover:text-blue-700 hover:underline transition duration-200 text-sm">
                    ¿Ya tienes una cuenta? Inicia sesión!
                </a>
            </div>
        </div>
        <div class="md:w-1/2">
            <img src="{{ asset('img/julio-cesar-1280-720.jpg') }}" alt="Imagen"
                class="object-cover w-full h-auto max-h-[700px] rounded-r-lg shadow-lg">
        </div>
    </div>

    <script>
        setupFieldValidation(["email", "userName"]);
    </script>

@endsection
