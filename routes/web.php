<?php

use App\Http\Controllers\Admin\ArtisanAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\MediaAdminController;
use App\Http\Controllers\Admin\ReservationAdminController;
use App\Http\Controllers\Admin\SavoirFaireAdminController;
use App\Http\Controllers\Admin\QuartierAdminController;
use App\Http\Controllers\ArtisanPublicController;
use App\Http\Controllers\ArtisanSpaceController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SavoirFairePublicController;
use App\Http\Controllers\Api\PitchlabFeaturesController;
use App\Http\Controllers\FeatureController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Publiques (lecture seule)
|--------------------------------------------------------------------------
*/
Route::post('/locale/{locale}', function (string $locale) {
    if (!in_array($locale, ['fr', 'en'])) {
        abort(400);
    }
    Session::put('locale', $locale);
    App::setLocale($locale);
    return redirect()->back();
})->name('locale.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/artisans', [ArtisanPublicController::class, 'index'])->name('artisans.index');

Route::get('/artisans/{artisan}/bridge', [FeatureController::class, 'showBridge'])->name('features.bridge.page');
Route::get('/artisans/{artisan}/voice', [FeatureController::class, 'showVoice'])->name('features.voice.page');
Route::get('/artisans/{artisan}/learn', [FeatureController::class, 'showLearn'])->name('features.learn.page');

Route::get('/artisans/{id}', [ArtisanPublicController::class, 'show'])->name('artisans.show');
Route::get('/savoir-faire',        [SavoirFairePublicController::class, 'index'])->name('savoir-faire.index');
Route::get('/savoir-faire/{slug}', [SavoirFairePublicController::class, 'show'])->name('savoir-faire.show');
Route::get('/experiences',         [ExperienceController::class, 'index'])->name('experiences.index');
Route::get('/carte',               [MapController::class, 'index'])->name('carte');

// ─── ƉƆKUN Learn ─────────────────────────────────────────────
Route::get('/learn',                          [LearnController::class, 'index'])->name('learn.index');
Route::get('/learn/{course}',                 [LearnController::class, 'course'])->name('learn.course');
Route::get('/learn/{course}/{lesson}',        [LearnController::class, 'play'])->name('learn.play');
Route::post('/learn/{lesson}/complete',       [LearnController::class, 'complete'])->name('learn.complete');

// ─── Espace visiteur / touriste ──────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/mon-voyage', [VisitorController::class, 'profile'])->name('visitor.profile');
    Route::post('/favoris/{artisan}', [VisitorController::class, 'toggleFavorite'])->name('visitor.favorites.toggle');
});

Route::get('/a-propos', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|max:5000',
    ]);
    try {
        \Illuminate\Support\Facades\Mail::raw(
            "Message de {$data['name']} ({$data['email']})\nSujet: {$data['subject']}\n\n{$data['message']}",
            function ($m) use ($data) {
                $m->to('contact@dokun.bj')->subject('[ƉƆKUN Contact] ' . $data['subject']);
            }
        );
    } catch (\Throwable $e) {
        // En local/dev : on n'interrompt pas l'utilisateur si le mail échoue
    }
    return back()->with('contact_success', true);
})->name('contact.send');

// ── Billet QR public (pas d'auth — le QR est le token)
Route::get('/reservations/{token}', [ReservationController::class, 'showByToken'])->name('reservations.receipt');

// ── Webhook FedaPay (sans CSRF)
Route::post('/api/fedapay/webhook', [PaymentController::class, 'webhook'])
    ->name('fedapay.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ── Callback FedaPay (redirection depuis la page de paiement)
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

/*
|--------------------------------------------------------------------------
| Routes Authentifiées — Réservation & Paiement
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Réservation — auth obligatoire
    Route::get('/artisans/{artisan_id}/reserver', [PaymentController::class, 'showConfirmation'])->name('payment.confirm');
    Route::post('/artisans/{artisan_id}/pay', [PaymentController::class, 'initiate'])->name('payment.initiate');

    // Scan QR (artisan ou admin)
    Route::match(['get', 'post'], '/reservations/{token}/scan', [ReservationController::class, 'scan'])
        ->name('reservations.scan');
});

/*
|--------------------------------------------------------------------------
| Routes Authentifiées — Profil & Avis
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Avis
    Route::get('/reservations/{reservation_id}/avis', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/avis', [ReviewController::class, 'store'])->name('reviews.store');
});

/*
|--------------------------------------------------------------------------
| Espace Artisan (role: artisan)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:artisan'])->prefix('mon-atelier')->name('artisan-space.')->group(function () {
    Route::get('/', [ArtisanSpaceController::class, 'index'])->name('index');
    Route::get('/profil', [ArtisanSpaceController::class, 'editProfile'])->name('edit-profile');
    Route::put('/profil', [ArtisanSpaceController::class, 'updateProfile'])->name('update-profile');
    Route::patch('/reservations/{reservation}', [ArtisanSpaceController::class, 'updateReservation'])->name('reservations.update');

    // Médias
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    // Voix
    Route::post('/voice/{artisan}/upload', [PitchlabFeaturesController::class, 'uploadVoice'])->name('voice.upload');
    Route::patch('/voice/{archiveId}', [PitchlabFeaturesController::class, 'updateVoiceArchive'])->name('voice.update');
    Route::delete('/voice/{archiveId}', [PitchlabFeaturesController::class, 'deleteVoiceArchive'])->name('voice.delete');

    // Photo de profil
    Route::post('/photo', [ArtisanSpaceController::class, 'uploadPhoto'])->name('photo.upload');

    // Réservation — JSON API (AJAX)
    Route::patch('/reservations/{reservation}/status-json', [ArtisanSpaceController::class, 'updateReservationStatus'])->name('reservations.status-json');
});

/*
|--------------------------------------------------------------------------
| Admin (role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reservations', [ReservationAdminController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{reservation}/status', [ReservationAdminController::class, 'updateStatus'])->name('reservations.updateStatus');
    Route::patch('/artisans/{artisan}/toggle', [ArtisanAdminController::class, 'toggleStatus'])->name('artisans.toggle');
    Route::resource('artisans',      ArtisanAdminController::class);
    Route::resource('categories',    CategoryAdminController::class);
    Route::resource('savoir-faires', SavoirFaireAdminController::class);
    Route::get('/carte', [MapController::class, 'adminMap'])->name('map');
    Route::get('/avis', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::patch('/avis/{review}/moderate', [ReviewController::class, 'adminModerate'])->name('reviews.moderate');

    // Médias
    Route::get('/media', [MediaAdminController::class, 'index'])->name('media.index');
    Route::patch('/media/{media}/moderate', [MediaAdminController::class, 'moderate'])->name('media.moderate');
    Route::delete('/media/{media}', [MediaAdminController::class, 'destroyMedia'])->name('media.destroy');
    Route::patch('/audio/{archiveId}/moderate', [MediaAdminController::class, 'moderateAudio'])->name('media.moderate-audio');
    Route::patch('/audio/{archiveId}', [MediaAdminController::class, 'updateAudio'])->name('media.update-audio');

    // Quartiers
    Route::get('/quartiers', [QuartierAdminController::class, 'index'])->name('quartiers.index');
    Route::post('/quartiers', [QuartierAdminController::class, 'store'])->name('quartiers.store');
    Route::patch('/quartiers/{quartier}', [QuartierAdminController::class, 'update'])->name('quartiers.update');
    Route::delete('/quartiers/{quartier}', [QuartierAdminController::class, 'destroy'])->name('quartiers.destroy');

    // Profil artisan — modération
    Route::patch('/artisans/{artisan}/approve-profile', [ArtisanAdminController::class, 'approveProfile'])->name('artisans.approve-profile');
    Route::patch('/artisans/{artisan}/reject-profile', [ArtisanAdminController::class, 'rejectProfile'])->name('artisans.reject-profile');
});

/*
|--------------------------------------------------------------------------
| ƉƆKUN Features API — Bridge (auth obligatoire)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('features')->name('features.')->group(function () {
    Route::get('/bridge/{artisan}/history', [PitchlabFeaturesController::class, 'getBridgeHistory'])->name('bridge.history');
    Route::post('/bridge/{artisan}',        [PitchlabFeaturesController::class, 'bridgeChat'])
        ->middleware('throttle:10,1')
        ->name('bridge');
});

// Voice archives & Learn — public (consultation uniquement)
Route::prefix('features')->name('features.')->group(function () {
    Route::get('/voice/{artisan}/archives', [PitchlabFeaturesController::class, 'getVoiceArchives'])->name('voice.archives');
    Route::get('/learn/{artisan}', [PitchlabFeaturesController::class, 'getLearningWords'])->name('learn');
    Route::post('/translate', [PitchlabFeaturesController::class, 'translateText'])->name('translate');
});

require __DIR__.'/auth.php';
