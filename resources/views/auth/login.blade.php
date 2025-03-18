@extends('layouts.app')
@section('title', 'Iniciar Sesión')
@section('content')
    <div class="flex flex-col md:flex-row justify-center min-h-[25vh] mt-[-2rem]">

        <div class="md:w-1/2 h-1/2">
            <img src="{{ asset('img/julio-cesar-1280-720.jpg') }}" alt="Imagen"
                class="object-cover w-full max-h-[700px] rounded-l-lg shadow-lg">
        </div>

        <div class="md:w-1/2 bg-white p-6 rounded-r-lg shadow-lg w-full max-w-md max-h-md overflow-y-auto">
            <h2 class="text-2xl font-semibold text-center text-blue-600 mb-4">Inicia sesión</h2>
            <p class="text-gray-600 text-center mb-6">Completa tus datos de usuario para poder ingresar a CaesarTalk.</p>
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div class="mb-3">
                    <label class="block text-gray-700 font-medium mb-1 text-sm" for="userName">Nombre de Usuario</label>
                    <input
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        type="text" name="userName" id="userName" data-bs-theme="light">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 font-medium mb-1 text-sm" for="password">Contraseña</label>
                    <input
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        type="password" name="password" id="password" data-bs-theme="light">
                </div>
                <div class="flex items-center">
                    <input class="text-blue-500 focus:ring focus:ring-blue-300" type="checkbox" name="remember"
                        id="checkbox" data-bs-theme="light">
                    <label class="ml-2 text-gray-700 text-sm" for="checkbox">Recuerdame</label>
                </div>
                <button class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200"
                    type="submit">Iniciar sesión</button>
                <div class="text-center mt-4">
                    <a href="{{ route('register') }}"
                        class="text-blue-600 hover:text-blue-700 hover:underline transition duration-200 text-sm">
                        ¿No tenes una cuenta? Registrate!
                    </a>
                </div>
                @if ($errors->has('userName'))
                    <p class="text-red-500 text-sm">{{ $errors->first('userName') }}</p>
                @endif
                @if ($errors->has('password'))
                    <p class="text-red-500 text-sm">{{ $errors->first('password') }}</p>
                @endif
            </form>
        </div>

</div>@endsection
