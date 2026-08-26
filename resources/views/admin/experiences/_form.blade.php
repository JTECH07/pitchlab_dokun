@php $exp = $experience ?? null; @endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
        <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Artisan *</label>
        <select name="artisan_id" required class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
            <option value="">Choisir…</option>
            @foreach($artisans as $a)
                <option value="{{ $a->id }}" {{ old('artisan_id', $exp->artisan_id ?? '') == $a->id ? 'selected' : '' }}>{{ $a->first_name }} {{ $a->last_name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('artisan_id')" class="mt-1.5"/>
    </div>

    <div class="sm:col-span-2">
        <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Titre *</label>
        <input type="text" name="title" value="{{ old('title', $exp->title ?? '') }}" required class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
        <x-input-error :messages="$errors->get('title')" class="mt-1.5"/>
    </div>

    <div class="sm:col-span-2">
        <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Description</label>
        <textarea name="summary" rows="3" class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">{{ old('summary', $exp->summary ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Durée (minutes) *</label>
        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $exp->duration_minutes ?? 120) }}" min="15" max="1440" required class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
        <x-input-error :messages="$errors->get('duration_minutes')" class="mt-1.5"/>
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Capacité *</label>
        <input type="number" name="capacity" value="{{ old('capacity', $exp->capacity ?? 6) }}" min="1" max="50" required class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
        <x-input-error :messages="$errors->get('capacity')" class="mt-1.5"/>
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Prix *</label>
        <input type="number" name="price" value="{{ old('price', $exp->price ?? 0) }}" min="0" step="100" required class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
        <x-input-error :messages="$errors->get('price')" class="mt-1.5"/>
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Devise</label>
        <select name="currency" class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
            @foreach(['XOF','EUR','USD'] as $c)
                <option value="{{ $c }}" {{ old('currency', $exp->currency ?? 'XOF') === $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Langue</label>
        <input type="text" name="language" value="{{ old('language', $exp->language ?? 'Français') }}" class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Image (URL ou chemin)</label>
        <input type="text" name="image_path" value="{{ old('image_path', $exp->image_path ?? '') }}" class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
    </div>

    <div class="sm:col-span-2">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $exp->is_published ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-dokun-green focus:ring-dokun-green">
            <span class="text-sm font-bold text-dokun-charcoal">Publiée</span>
        </label>
    </div>
</div>
