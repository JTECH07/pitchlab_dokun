{{-- Quartier form fields — used by create & edit modals --}}
<div>
    <label class="text-xs font-bold text-slate-500 uppercase">Nom du quartier</label>
    <input type="text" name="name" x-model="form.name" required
           class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-green/20 focus:border-dokun-green outline-none">
</div>
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="text-xs font-bold text-slate-500 uppercase">Latitude</label>
        <input type="number" step="any" name="lat" x-model="form.lat" required
               class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-green/20 focus:border-dokun-green outline-none">
    </div>
    <div>
        <label class="text-xs font-bold text-slate-500 uppercase">Longitude</label>
        <input type="number" step="any" name="lng" x-model="form.lng" required
               class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-green/20 focus:border-dokun-green outline-none">
    </div>
</div>
<div class="grid grid-cols-3 gap-3">
    <div>
        <label class="text-xs font-bold text-slate-500 uppercase">Rayon (km)</label>
        <input type="number" step="0.1" name="radius_km" x-model="form.radius_km"
               class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-green/20 focus:border-dokun-green outline-none">
    </div>
    <div>
        <label class="text-xs font-bold text-slate-500 uppercase">Couleur</label>
        <input type="color" name="color" x-model="form.color"
               class="w-full mt-1 h-10 px-1 py-1 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
    </div>
    <div>
        <label class="text-xs font-bold text-slate-500 uppercase">Ordre</label>
        <input type="number" name="sort_order" x-model="form.sort_order"
               class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-green/20 focus:border-dokun-green outline-none">
    </div>
</div>
<div class="flex justify-end gap-3 pt-2">
    <button type="button" @click="open = false" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 transition">Annuler</button>
    <button type="submit" class="px-6 py-2.5 bg-dokun-green hover:bg-dokun-green/90 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">Enregistrer</button>
</div>
