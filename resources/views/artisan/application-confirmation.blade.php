<x-app-layout>
    <x-slot name="header">
        <h1 class="font-serif text-3xl text-dokun-green">Candidature soumise</h1>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-black/5 shadow-sm p-8 text-center">
            <div class="w-16 h-16 bg-dokun-gold/15 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="font-serif text-2xl text-dokun-green mb-3">Merci {{ $application->first_name }} !</h2>
            <p class="text-sm text-dokun-charcoal/60 mb-2">Votre candidature a bien été envoyée à notre équipe.</p>
            <p class="text-sm text-dokun-charcoal/60 mb-6">Vous recevrez un email dès que votre dossier aura été examiné.</p>
            <div class="bg-[#F8F6F0] rounded-xl p-4 text-left text-sm space-y-1.5">
                <p><span class="font-bold text-dokun-charcoal/50">Statut :</span> <span class="font-bold text-amber-700">En attente de validation</span></p>
                <p><span class="font-bold text-dokun-charcoal/50">Catégorie :</span> {{ $application->category->name ?? '—' }}</p>
                <p><span class="font-bold text-dokun-charcoal/50">Adresse :</span> {{ $application->address }}</p>
            </div>
            <a href="{{ route('home') }}" class="inline-block mt-6 text-sm font-bold text-dokun-green hover:underline">Retour à l'accueil</a>
        </div>
    </div>
</x-app-layout>
