@extends('admin.layouts.admin')

@section('title', 'Modifier : ' . $experience->title)
@section('page-title', 'Modifier : ' . $experience->title)

@section('content')
<div class="max-w-3xl mx-auto">
 <a href="{{ route('admin.experiences.index') }}" class="inline-flex items-center gap-2 text-dokun-green hover:underline text-sm font-bold mb-6">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
 Retour aux expériences
 </a>

 <form method="POST" action="{{ route('admin.experiences.update', $experience) }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-black/5 shadow-sm p-6 space-y-5">
 @csrf @method('PUT')
 @include('admin.experiences._form')
 <div class="flex flex-col sm:flex-row gap-3 pt-2">
 <button type="submit" class="flex-1 py-3 bg-dokun-green text-white font-bold rounded-xl hover:bg-dokun-green/90 transition text-sm">Enregistrer</button>
 <a href="{{ route('admin.experiences.index') }}" class="py-3 px-6 text-center border-2 border-gray-200 text-dokun-charcoal/60 font-bold rounded-xl hover:bg-gray-50 transition text-sm">Annuler</a>
 </div>
 </form>
</div>
@endsection
