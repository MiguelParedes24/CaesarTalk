@extends('layouts.app')
@section('title', 'CaesarTalk')

@section('content')
    <section class=" w-full mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-center">

            <div class="md:w-1/4">
                <div class="relative h-32 md:h-auto mx-auto">
                    <img src="{{ asset('img/julio-cesar2-1280-720.jpg') }}" alt="Imagen de julio Cesar"
                        class="object-cover w-full h-full rounded-lg shadow-lg">
                </div>
            </div>

            <div class="md:w-1/2 md:pl-8">
                <h2 class="text-3xl font-bold text-white mb-6 text-center md:text-left">
                    ¿Sabías qué?
                </h2>
                <div class="prose lg:prose-2xl text-gray-200 mx-auto text-center md:text-left">
                    <p>El cifrado César es uno de los métodos de cifrado más antiguos y sencillos que se conocen. Se
                        atribuye a Julio César, quien lo utilizaba para comunicarse con sus generales en el campo de
                        batalla.</p>
                    <p>Consiste en desplazar cada letra del mensaje original un número fijo de posiciones en el alfabeto.
                        Por ejemplo, si desplazamos cada letra tres posiciones, la letra 'A' se convertiría en 'D', la 'B'
                        en 'E', y así sucesivamente.</p>
                    <br><br>
                    @guest
                        <a href="{{ route('register') }}"
                            class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                            Pruebalo ahora
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>
@endsection
