@extends('admin.layouts.admin')
@section('title', 'Gestion des Quartiers')
@section('page-title', 'Quartiers de Porto-Novo')

@section('content')
<div class="space-y-6" x-data="quartierModal()">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-500">{{ $quartiers->count() }} quartier(s) enregistré(s)</p>
        <button @click="openCreate()" class="px-5 py-2.5 bg-dokun-green hover:bg-dokun-green/90 text-white text-sm font-bold rounded-xl transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau quartier
        </button>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="px-5 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 font-semibold">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="px-5 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 font-semibold">
            @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200/80 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Couleur</th>
                    <th class="px-6 py-4">Nom</th>
                    <th class="px-6 py-4">Latitude</th>
                    <th class="px-6 py-4">Longitude</th>
                    <th class="px-6 py-4">Rayon</th>
                    <th class="px-6 py-4">Ordre</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($quartiers as $q)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4"><span class="inline-block w-6 h-6 rounded-full border border-slate-200" style="background:{{ $q->color }}"></span></td>
                    <td class="px-6 py-4 font-bold text-slate-900">{{ $q->name }}</td>
                    <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $q->lat }}</td>
                    <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $q->lng }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $q->radius_km }} km</td>
                    <td class="px-6 py-4 text-slate-500">{{ $q->sort_order }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button @click="openEdit(@js($q->slug), @js($q->name), '{{ $q->lat }}', '{{ $q->lng }}', '{{ $q->radius_km }}', '{{ $q->color }}', '{{ $q->sort_order }}')"
                                    class="px-3 py-1.5 bg-dokun-gold/10 hover:bg-dokun-gold/20 text-dokun-gold text-xs font-bold rounded-lg transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Éditer
                            </button>
                            <form action="{{ route('admin.quartiers.destroy', $q) }}" method="POST" onsubmit="return confirm('Supprimer ce quartier ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-lg transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-slate-400 text-sm">Aucun quartier enregistré.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Single Modal --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-[1100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" @click="open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6" x-transition>
            <h3 class="font-serif text-xl text-dokun-green mb-4" x-text="isEdit ? 'Éditer le quartier' : 'Nouveau quartier'"></h3>

            <form :action="formAction" method="POST" class="space-y-3">
                @csrf
                <template x-if="isEdit"><input type="hidden" name="_method" value="PATCH"></template>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Nom du quartier</label>
                    <input type="text" name="name" x-model="form.name" required class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-green/20 focus:border-dokun-green outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Latitude</label>
                        <input type="number" step="any" name="lat" x-model="form.lat" required class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-green/20 focus:border-dokun-green outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Longitude</label>
                        <input type="number" step="any" name="lng" x-model="form.lng" required class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-green/20 focus:border-dokun-green outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Rayon (km)</label>
                        <input type="number" step="0.1" name="radius_km" x-model="form.radius_km" class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-green/20 focus:border-dokun-green outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Couleur</label>
                        <input type="color" name="color" x-model="form.color" class="w-full mt-1 h-10 px-1 py-1 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Ordre</label>
                        <input type="number" name="sort_order" x-model="form.sort_order" class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-green/20 focus:border-dokun-green outline-none">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="open = false" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 transition">Annuler</button>
                    <button type="submit" class="px-6 py-2.5 bg-dokun-green hover:bg-dokun-green/90 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function quartierModal() {
    return {
        open: false,
        isEdit: false,
        slug: '',
        form: { name: '', lat: '', lng: '', radius_km: '0.4', color: '#064E3B', sort_order: '0' },
        get formAction() {
            return this.isEdit ? '{{ url("admin/quartiers") }}/' + this.slug : '{{ route("admin.quartiers.store") }}';
        },
        openCreate() {
            this.isEdit = false;
            this.slug = '';
            this.form = { name: '', lat: '', lng: '', radius_km: '0.4', color: '#064E3B', sort_order: '0' };
            this.open = true;
        },
        openEdit(slug, name, lat, lng, radius, color, order) {
            this.isEdit = true;
            this.slug = slug;
            this.form = { name, lat, lng, radius_km: radius, color, sort_order: order };
            this.open = true;
        }
    }
}
</script>
@endsection
