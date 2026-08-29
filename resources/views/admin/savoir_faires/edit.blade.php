@extends('admin.layouts.admin')
@section('title', 'Modifier Savoir-Faire')
@section('page-title', 'Modifier le Savoir-Faire')

@section('content')
<div class="max-w-xl">
 <form action="{{ route('admin.savoir-faires.update', $savoirFaire) }}" method="POST" class="space-y-6">
 @csrf
 @method('PUT')
 <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
 <div>
 <label class="block text-sm font-bold text-slate-700 mb-1">Catégorie *</label>
 <select name="category_id" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
 @foreach($categories as $cat)
 <option value="{{ $cat->id }}" {{ old('category_id', $savoirFaire->category_id) == $cat->id ? 'selected' : '' }}>
 {{ $cat->name }}
 </option>
 @endforeach
 </select>
 </div>
 <div>
 <label class="block text-sm font-bold text-slate-700 mb-1">Nom du Savoir-Faire *</label>
 <input type="text" name="name" value="{{ old('name', $savoirFaire->name) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
 </div>
 <div>
 <label class="block text-sm font-bold text-slate-700 mb-1">Description *</label>
 <textarea name="description" rows="4" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 resize-none">{{ old('description', $savoirFaire->description) }}</textarea>
 </div>
 </div>

 <div class="flex gap-4">
 <button type="submit" class="px-6 py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 shadow-lg shadow-amber-500/20 transition-colors">
 Mettre à jour
 </button>
 <a href="{{ route('admin.savoir-faires.index') }}" class="px-6 py-3 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
 Annuler
 </a>
 </div>
 </form>
</div>
@endsection
