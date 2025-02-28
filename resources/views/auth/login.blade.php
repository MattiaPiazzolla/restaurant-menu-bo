@extends('layouts.app')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-8 pt-8 pb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ __('Bentornato') }}</h2>
                <p class="text-gray-500 text-sm">{{ __('Accedi al tuo account') }}</p>
            </div>
            <div class="px-8 pb-8">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Indirizzo email') }}
                        </label>
                        <input id="email" type="email" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-800 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 @error('email') border-red-300 ring-1 ring-red-300 @enderror" 
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nome@esempio.com">
                        @error('email')
                        <span class="text-sm text-red-500 mt-1 block" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                {{ __('Password') }}
                            </label>
                            
                        </div>
                        <input id="password" type="password" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-800 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 @error('password') border-red-300 ring-1 ring-red-300 @enderror" 
                            name="password" required autocomplete="current-password" placeholder="••••••••">
                        @error('password')
                        <span class="text-sm text-red-500 mt-1 block" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input class="h-4 w-4 rounded-md border-gray-300 text-primary focus:ring-primary transition duration-150 ease-in-out" 
                            type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="ml-2 block text-sm text-gray-700" for="remember">
                            {{ __('Ricordami') }}
                        </label>
                    </div>

                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                            {{ __('Accedi') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection