<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.experiences.index') }}" class="text-dokun-green hover:underline text-sm font-bold">← Expériences</a>
            <h1 class="font-serif text-3xl text-dokun-green">Modifier : {{ $experience->title }}</h1>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.experiences.update', $experience) }}" class="bg-white rounded-2xl border border-black/5 shadow-sm p-6 space-y-5">
            @csrf @method('PUT')
            @include('admin.experiences._form')
            <button type="submit" class="w-full py-3 bg-dokun-green text-white font-bold rounded-xl hover:bg-dokun-green/90 transition text-sm">Enregistrer</button>
        </form>
    </div>
</x-app-layout>
