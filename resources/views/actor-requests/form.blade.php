<x-guest-layout>
 <div class="text-center mb-8">
 <h1 class="font-serif text-3xl text-[#064E3B] mb-2">{{ __('app.actor_form_title') }}</h1>
 <p class="text-[#17201D]/60 text-sm">{{ __('app.actor_form_subtitle') }}</p>
 </div>

 @if($errors->any())
 <div class="mb-5 rounded-xl bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 text-sm font-semibold">
 {{ __('app.actor_form_errors') }}
 </div>
 @endif

 <form method="POST" action="{{ route('actor-requests.submit') }}" class="space-y-5" x-data="{ role: '{{ old('role') }}' }">
 @csrf

 {{-- Sélection du rôle --}}
 <div>
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
 @foreach($roles as $key => $meta)
 <label class="relative cursor-pointer rounded-2xl border-2 p-3.5 transition-all duration-200"
 :class="role === '{{ $key }}' ? 'border-[#C99424] bg-[#C99424]/5 shadow-md' : 'border-gray-200 hover:border-gray-300'">
 <input type="radio" name="role" value="{{ $key }}" class="sr-only" x-model="role">
 <span class="flex items-start gap-3">
 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
 class="w-8 h-8 flex-shrink-0 transition-colors" :class="role === '{{ $key }}' ? 'text-[#C99424]' : 'text-gray-400'">
 <path d="{{ $meta['icon'] }}"/>
 </svg>
 <span class="block">
 <span class="block font-bold text-sm" :class="role === '{{ $key }}' ? 'text-[#C99424]' : 'text-[#17201D]'">{{ $meta['label'] }}</span>
 <span class="block text-xs text-[#17201D]/50 mt-0.5 leading-snug">{{ $meta['desc'] }}</span>
 </span>
 </span>
 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="w-4 h-4 absolute top-1.5 right-1.5 text-[#C99424]" x-show="role === '{{ $key }}'" x-cloak><path d="M20 6L9 17l-5-5"/></svg>
 </label>
 @endforeach
 </div>
 <x-input-error :messages="$errors->get('role')" class="mt-1"/>
 </div>

 {{-- Infos personnelles --}}
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 <div>
 <label for="name" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.actor_form_full_name') }} *</label>
 <input type="text" id="name" name="name" value="{{ old('name') }}" required
 class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
 placeholder="{{ __('app.actor_form_name_placeholder') }}">
 <x-input-error :messages="$errors->get('name')" class="mt-1.5"/>
 </div>
 <div>
 <label for="email" class="block text-sm font-bold text-[#17201D] mb-2">Email *</label>
 <input type="email" id="email" name="email" value="{{ old('email') }}" required
 class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
 placeholder="vous@exemple.com">
 <x-input-error :messages="$errors->get('email')" class="mt-1.5"/>
 </div>
 <div>
 <label for="phone" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.actor_form_phone') }} <span class="text-[#17201D]/30 normal-case">({{ __('app.actor_form_optional') }})</span></label>
 <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
 class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
 placeholder="+229 97 00 00 00">
 </div>
 <div>
 <label for="organization" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.actor_form_org') }} <span class="text-[#17201D]/30 normal-case">({{ __('app.actor_form_optional') }})</span></label>
 <input type="text" id="organization" name="organization" value="{{ old('organization') }}"
 class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
 placeholder="{{ __('app.actor_form_org_placeholder') }}">
 </div>
 </div>

 {{-- Champs spécifiques selon le rôle choisi --}}
 @foreach($roles as $key => $meta)
 @if(!empty($meta['fields']))
 <div x-show="role === '{{ $key }}'" x-cloak class="space-y-4 rounded-2xl border border-[#C99424]/30 bg-[#C99424]/5 p-5">
 <p class="text-xs font-bold uppercase tracking-wider text-[#C99424]">{{ __('app.actor_form_role_specific', ['role' => $meta['label']]) }}</p>
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 @foreach($meta['fields'] as $fkey => $field)
 @php
 $reqTag = ($field['required'] ?? false) ? ' *' : ' ('.__('app.actor_form_optional').')';
 $oldVal = old($fkey);
 $inputId = 'field_'.$fkey;
 @endphp
 <div @if(($field['type'] ?? 'text') === 'textarea') class="sm:col-span-2" @endif>
 @if(($field['type'] ?? 'text') === 'select')
 <label for="{{ $inputId }}" class="block text-sm font-bold text-[#17201D] mb-2">{{ $field['label'] }}{{ $reqTag }}</label>
 <select id="{{ $inputId }}" name="{{ $fkey }}" @if($field['required'] ?? false) required @endif
 class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm">
 <option value="">— {{ __('app.actor_form_choose') }} —</option>
 @foreach($field['options'] ?? [] as $oval => $olabel)
 <option value="{{ $oval }}" @selected($oldVal === $oval)>{{ $olabel }}</option>
 @endforeach
 </select>
 @elseif(($field['type'] ?? 'text') === 'textarea')
 <label for="{{ $inputId }}" class="block text-sm font-bold text-[#17201D] mb-2">{{ $field['label'] }}{{ $reqTag }}</label>
 <textarea id="{{ $inputId }}" name="{{ $fkey }}" rows="3" @if($field['required'] ?? false) required @endif maxlength="2000"
 class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
 placeholder="{{ $field['placeholder'] ?? '' }}">{{ $oldVal }}</textarea>
 @else
 <label for="{{ $inputId }}" class="block text-sm font-bold text-[#17201D] mb-2">{{ $field['label'] }}{{ $reqTag }}</label>
 <input type="{{ $field['type'] ?? 'text' }}" id="{{ $inputId }}" name="{{ $fkey }}" value="{{ $oldVal }}"
 @if($field['required'] ?? false) required @endif maxlength="2000"
 class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
 placeholder="{{ $field['placeholder'] ?? '' }}">
 @endif
 <x-input-error :messages="$errors->get($fkey)" class="mt-1"/>
 </div>
 @endforeach
 </div>
 </div>
 @endif
 @endforeach

 {{-- Motivation --}}
 <div>
 <label for="motivation" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.actor_form_motivation') }} *</label>
 <textarea id="motivation" name="motivation" rows="3" required maxlength="2000"
 class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
 placeholder="{{ __('app.actor_form_motivation_placeholder') }}">{{ old('motivation') }}</textarea>
 <x-input-error :messages="$errors->get('motivation')" class="mt-1.5"/>
 </div>

 <button type="submit" class="w-full py-4 bg-[#064E3B] text-white font-bold rounded-xl hover:bg-[#064E3B]/90 transition-all shadow-lg text-base">
 {{ __('app.actor_form_submit') }}
 </button>
 </form>

 <div class="mt-6 text-center text-sm text-[#17201D]/60">
 {{ __('app.actor_form_already') }}
 <a href="{{ route('login') }}" class="text-[#C99424] font-bold hover:underline ml-1">{{ __('app.actor_form_login') }}</a>
 </div>
</x-guest-layout>
