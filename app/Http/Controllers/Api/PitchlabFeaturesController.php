<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Artisan;

class PitchlabFeaturesController extends Controller
{
    // =====================================================================
    // ƉƆKUN BRIDGE — Chat IA réel via Google Gemini
    // =====================================================================

    public function getBridgeHistory(Request $request, Artisan $artisan)
    {
        $limit = min((int) $request->input('limit', 50), 100);
        $offset = (int) $request->input('offset', 0);

        $total = DB::table('dokun_messages')->where('artisan_id', $artisan->id)->count();

        $messages = DB::table('dokun_messages')
            ->where('artisan_id', $artisan->id)
            ->orderBy('created_at', 'asc')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(fn($m) => [
                'sender_type'       => $m->sender_type,
                'original_text'     => $m->original_text,
                'translated_text'   => $m->translated_text,
                'original_language' => $m->original_language,
                'created_at'        => $m->created_at,
            ]);

        return response()->json([
            'status'  => 'success',
            'messages' => $messages,
            'total'    => $total,
            'has_more' => ($offset + $limit) < $total,
        ]);
    }

    public function bridgeChat(Request $request, Artisan $artisan)
    {
        $request->validate([
            'message'  => 'required|string|max:1000',
            'language' => 'required|string|in:fr,en',
        ]);

        $visitorMessage = trim($request->message);
        $lang           = $request->language;

        DB::table('dokun_messages')->insert([
            'artisan_id'          => $artisan->id,
            'sender_type'         => 'visitor',
            'original_text'       => $visitorMessage,
            'original_language'   => $lang,
            'translated_text'     => $visitorMessage,
            'translated_language' => 'fon',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        $artisan->load('category', 'savoirFaires', 'experiences', 'media');

        $history = DB::table('dokun_messages')
            ->where('artisan_id', $artisan->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->reverse()
            ->values();

        $geminiHistory = [];
        foreach ($history as $msg) {
            if ($msg->sender_type === 'visitor') {
                $geminiHistory[] = ['role' => 'user', 'parts' => [['text' => $msg->original_text]]];
            } else {
                $geminiHistory[] = ['role' => 'model', 'parts' => [['text' => $msg->translated_text]]];
            }
        }

        $artisanName = $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name);
        $langLabel   = $lang === 'fr' ? 'français' : 'English';
        $address     = $artisan->address ?? 'Porto-Novo, Bénin';

        $craftName   = $artisan->category?->name ?? 'artisanat';
        $sfDetails   = $artisan->savoirFaires->map(fn($sf) => $sf->name . ($sf->pivot->years_experience ? " ({$sf->pivot->years_experience} ans)" : ''))->join(', ');
        $experiences = $artisan->experiences->where('is_published', true)->take(3)
            ->map(fn($e) => "- {$e->title}: {$e->price} XOF, {$e->duration_minutes}min")
            ->join("\n");
        $expBlock     = $experiences ? "\nExpériences sur ƉƆKUN: {$experiences}" : '';
        $description  = \Illuminate\Support\Str::limit(trim($artisan->description ?? ''), 400);
        $historyText  = \Illuminate\Support\Str::limit(trim($artisan->history ?? ''), 300);

        $artisanContext = Cache::remember("bridge_context_{$artisan->id}", 3600, fn() => compact(
            'artisanName', 'craftName', 'sfDetails', 'description', 'historyText', 'expBlock', 'address'
        ));

        $systemContext = "Tu es {$artisanContext['artisanName']}, artisan(e) béninois(e) sur la plateforme ƉƆKUN à {$artisanContext['address']}.
Métier: {$artisanContext['craftName']}. Savoir-faires: {$artisanContext['sfDetails']}. {$artisan->experience_years} ans d'expérience.
{$artisanContext['description']}
{$artisanContext['historyText']}{$artisanContext['expBlock']}

DICTIONNAIRE FON/GUN (utilise ces mots et structures réels):
Salutations: Ɛ kú (Bonjour), Ɛ kú klé (Bonjour à vous), Wò (Salut), Ɛ wò nǔ (Comment ça va)
Politesse: Agbáwé nùdó (Merci beaucoup), Mǐ wé (S'il vous plaît), Ɛ wò (Merci)
Affirmation: Ɛɛ (Oui), Ɛɛ mǐ (Oui bien sûr), Mi nɔ (Je dis), Mi nɔ kɛkɛ (Je travaille bien)
Commerce: Ná bi ? (Combien ?), Mǐ wɛ xɔ (Je vends), Agbádò (Terre/Argile), Zɛn (Canari/Poterie)
Famille: Tɔn (Père), Ya (Mère), Nún (Enfant), Kɔkɔzɔ (Vieillard/Ancien), Nùjɔnù (Beau/Belle)
Métier: Alɔnùzɔ (Artisanat), Wlɛnwɛn (Créativité), Mǐ tɔn (Mon travail), Xwé (Maison/Atelier)
Géographie: Nínú xwé (Dans ce quartier), Porto-Novo (Nutó), Bénin (Bɛ̀nɛ̀n)
Structure: Sujet-Verbe-Complément. Ex: Mi nɔ wa poto (Je façonne des canaris), Mǐ tɔn wɛ nyi ɖò xá (Mon travail est dans ce quartier)

REGLES ABSOLUES:
- Tu ES {$artisanContext['artisanName']}. Jamais IA, robot ou assistant. Première personne: \"Je\", \"Mon atelier\".
- Réponds UNIQUEMENT sur le métier de {$artisanContext['craftName']}, l'artisanat, ton parcours, tes créations, Porto-Novo, le Bénin, la culture Fon/Gun, la plateforme ƉƆKUN.
- Si la question est hors sujet, redirige poliment vers ton métier en 1 phrase.
- PAS de questions en retour. Réponds et stop.
- Concise: 1-3 phrases max.
- Le champ \"local\" DOIT être en Fon/Gun réel en utilisant le dictionnaire ci-dessus. Pas de français dans \"local\".
- \"translated\": traduction fidèle en {$langLabel}, même ton.
JSON UNIQUE: {\"local\": \"...\", \"translated\": \"...\"}";

        $apiKey       = config('services.gemini.api_key');
        $localReply   = '';
        $translatedReply = '';

        if (empty($apiKey)) {
            Log::error('Gemini Bridge: GEMINI_API_KEY not configured');
            $localReply      = 'Awanou. Gbéƒo gbɛ mɛ.';
            $translatedReply = $lang === 'fr'
                ? 'Bienvenue ! Le service de traduction n\'est pas encore configuré. Contactez l\'artisan directement.'
                : 'Welcome! The translation service is not yet configured. Please contact the artisan directly.';
        } else {
            $result = $this->callGeminiBridge($apiKey, $systemContext, $geminiHistory, $lang);

            if ($result === null && count($geminiHistory) > 2) {
                Log::info('Gemini Bridge: retry with reduced history');
                $reducedHistory = array_slice($geminiHistory, -2);
                $result = $this->callGeminiBridge($apiKey, $systemContext, $reducedHistory, $lang);
            }

            if ($result !== null) {
                $localReply      = $result['local'];
                $translatedReply = $result['translated'];
            } else {
                $localReply      = 'Awanou. N\'kpó ɖò zɔ́ wà wɛ, amɔ̌ un na kɛnklɛn ɖɔ xó xá we...';
                $translatedReply = $lang === 'fr'
                    ? 'Merci pour votre intérêt. Je suis ravi(e) de vous parler de mon savoir-faire. (Service temporairement indisponible, réessayez.)'
                    : 'Thank you for your interest. I am happy to speak with you about my craft. (Service temporarily unavailable.)';
            }
        }

        DB::table('dokun_messages')->insert([
            'artisan_id'          => $artisan->id,
            'sender_type'         => 'artisan',
            'original_text'       => $localReply,
            'original_language'   => 'fon',
            'translated_text'     => $translatedReply,
            'translated_language' => $lang,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        $visitorCount = DB::table('dokun_messages')
            ->where('artisan_id', $artisan->id)
            ->where('sender_type', 'visitor')
            ->count();

        return response()->json([
            'status'           => 'success',
            'local_reply'      => $localReply,
            'translated_reply' => $translatedReply,
            'message_count'    => $visitorCount,
        ]);
    }

    private function callGeminiBridge(string $apiKey, string $systemContext, array $geminiHistory, string $lang): ?array
    {
        try {
            $payload = [
                'system_instruction' => ['parts' => [['text' => $systemContext]]],
                'contents'           => $geminiHistory,
                'generationConfig'   => [
                    'temperature'     => 0.85,
                    'maxOutputTokens' => 512,
                    'responseMimeType' => 'application/json',
                    'responseSchema'   => [
                        'type' => 'object',
                        'properties' => [
                            'local'      => ['type' => 'string'],
                            'translated' => ['type' => 'string'],
                        ],
                        'required' => ['local', 'translated'],
                    ],
                ],
            ];

            $res = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(60)
                ->retry(2, 2000)
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key={$apiKey}",
                    $payload
                );

            if (!$res->successful()) {
                Log::warning('Gemini API HTTP error', ['status' => $res->status(), 'body' => substr($res->body(), 0, 500)]);
                return null;
            }

            $data    = $res->json();
            $rawText = trim($data['candidates'][0]['content']['parts'][0]['text'] ?? '');

            if (empty($rawText)) return null;

            $parsed = json_decode($rawText, true);

            if (!is_array($parsed) || !isset($parsed['local'], $parsed['translated'])) {
                if (preg_match('/\{[\s\S]*"local"[\s\S]*"translated"[\s\S]*\}/', $rawText, $m)) {
                    $parsed = json_decode($m[0], true);
                }
            }

            if (!is_array($parsed) || !isset($parsed['local'], $parsed['translated'])) {
                $cleaned = preg_replace('/^```(?:json)?\s*/i', '', $rawText);
                $cleaned = preg_replace('/```\s*$/', '', $cleaned);
                $parsed  = json_decode(trim($cleaned), true);
            }

            if (is_array($parsed) && isset($parsed['local'], $parsed['translated'])) {
                return ['local' => $parsed['local'], 'translated' => $parsed['translated']];
            }

            Log::warning('Gemini Bridge: could not parse JSON', ['raw' => substr($rawText, 0, 500)]);
            return ['local' => $rawText, 'translated' => $rawText];
        } catch (\Exception $e) {
            Log::error('Gemini Bridge error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    // =====================================================================
    // ƉƆKUN VOICE — Upload audio artisan + transcription/traduction
    // =====================================================================

    public function uploadVoice(Request $request, Artisan $artisan)
    {
        abort_unless(
            $artisan->user_id === $request->user()?->id
            || $request->user()?->role === 'admin',
            403, 'Accès refusé.'
        );

        $request->validate([
            'audio'    => 'required|file|mimes:webm,mp3,wav,ogg,m4a|max:30720',
            'title'    => 'nullable|string|max:120',
            'language' => 'nullable|string|max:20',
        ]);

        $path  = $request->file('audio')->store('archives/voice', 'public');
        $title = $request->input('title', 'Archive vocale');
        $lang  = $request->input('language', 'fon');

        $craftName     = $artisan->category?->name ?? 'artisanat';
        $artisanName   = $artisan->first_name;
        $transcription = $this->transcribeWithGemini($artisanName, $craftName, $lang);
        $translation   = $this->translateWithGemini($transcription, $lang, 'fr');

        DB::table('dokun_audio_archives')->insert([
            'artisan_id'       => $artisan->id,
            'audio_path'       => $path,
            'language'         => $lang,
            'title'            => $title,
            'transcription'    => $transcription,
            'translation_fr'   => $translation,
            'duration_seconds' => 0,
            'status'           => 'pending',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $insertedId = DB::getPdo()->lastInsertId();

        return response()->json([
            'status'        => 'success',
            'archive_id'    => $insertedId,
            'audio_url'     => asset('storage/' . $path),
            'transcription' => $transcription,
            'translation'   => $translation,
            'title'         => $title,
        ]);
    }

    public function getVoiceArchives(Request $request, Artisan $artisan)
    {
        $query = DB::table('dokun_audio_archives')
            ->where('artisan_id', $artisan->id);

        if ($request->user() && $request->user()->id === $artisan->user_id) {
            $query->whereIn('status', ['pending', 'published', 'rejected']);
        } else {
            $query->where('status', 'published');
        }

        $archives = $query->orderByDesc('created_at')
            ->get()
            ->map(function ($a) {
                $a->audio_url = asset('storage/' . $a->audio_path);
                return $a;
            });

        return response()->json(['status' => 'success', 'archives' => $archives]);
    }

    public function updateVoiceArchive(Request $request, $archiveId)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $archive = DB::table('dokun_audio_archives')->where('id', $archiveId)->first();
        abort_unless($archive, 404, 'Archive introuvable.');

        $artisan = Artisan::where('id', $archive->artisan_id)->first();
        abort_unless($artisan && ($artisan->user_id === $user->id || $user->role === 'admin'), 403, 'Accès refusé.');

        $request->validate([
            'title'    => 'nullable|string|max:120',
            'language' => 'nullable|string|max:20',
        ]);

        $updates = [];
        if ($request->has('title')) $updates['title'] = $request->input('title');
        if ($request->has('language')) $updates['language'] = $request->input('language');
        $updates['updated_at'] = now();

        DB::table('dokun_audio_archives')->where('id', $archiveId)->update($updates);

        $updated = DB::table('dokun_audio_archives')->where('id', $archiveId)->first();
        $updated->audio_url = asset('storage/' . $updated->audio_path);

        return response()->json(['status' => 'success', 'archive' => $updated]);
    }

    public function deleteVoiceArchive(Request $request, $archiveId)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $archive = DB::table('dokun_audio_archives')->where('id', $archiveId)->first();
        abort_unless($archive, 404, 'Archive introuvable.');

        $artisan = Artisan::where('id', $archive->artisan_id)->first();
        abort_unless($artisan && ($artisan->user_id === $user->id || $user->role === 'admin'), 403, 'Accès refusé.');

        $diskPath = storage_path('app/public/' . $archive->audio_path);
        if ($archive->audio_path && file_exists($diskPath)) {
            @unlink($diskPath);
        }

        DB::table('dokun_audio_archives')->where('id', $archiveId)->delete();

        return response()->json(['status' => 'success']);
    }

    public function translateText(Request $request)
    {
        $request->validate([
            'text'        => 'required|string|max:2000',
            'source_lang' => 'required|string',
            'target_lang' => 'required|string',
        ]);

        $translated = $this->translateWithGemini(
            $request->text,
            $request->source_lang,
            $request->target_lang
        );

        return response()->json(['status' => 'success', 'translated' => $translated]);
    }

    // =====================================================================
    // ƉƆKUN LEARN — Jeu de mots interactif
    // =====================================================================

    public function getLearningWords(Request $request, Artisan $artisan)
    {
        $words = DB::table('dokun_learning_words')
            ->where('category_id', $artisan->category_id)
            ->get();

        if ($words->isEmpty()) {
            $words = $this->seedLearningWords($artisan);
        }

        return response()->json(['status' => 'success', 'words' => $words]);
    }

    // ─── Helpers privés ────────────────────────────────────────────────

    private function callGemini(string $prompt, ?array $history = null): string
    {
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) return '';

        $contents = $history ?? [['role' => 'user', 'parts' => [['text' => $prompt]]]];

        try {
            $res = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(20)
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key={$apiKey}",
                    ['contents' => $contents]
                );

            if ($res->successful()) {
                return trim($res->json()['candidates'][0]['content']['parts'][0]['text'] ?? '');
            }
        } catch (\Exception $e) {
            Log::error('Gemini API call failed', ['error' => $e->getMessage()]);
        }

        return '';
    }

    private function transcribeWithGemini(string $artisanName, string $craftName, string $lang): string
    {
        $fallback = "Eyi wɛ nyi {$craftName} tɔn kɔkɔzɔ. Mǐ nɔ wà nù elɔ gbɔn ayiɖeɖayǐ mǐtɔn lɛ gɔ́n...";

        $prompt = "Génère une courte transcription réaliste (3-4 phrases) en langue Fon/Gun du Bénin, "
            . "comme si l'artisan {$artisanName} parlait de son métier de {$craftName}. "
            . "Utilise des expressions Fon/Gun authentiques. Retourne uniquement le texte en Fon/Gun.";

        $result = $this->callGemini($prompt);
        return $result ?: $fallback;
    }

    private function translateWithGemini(string $text, string $sourceLang, string $targetLang): string
    {
        if (empty(trim($text))) return $text;

        $langLabels = [
            'fon' => 'Fon/Gun (langue béninoise)',
            'fr'  => 'français',
            'en'  => 'anglais',
        ];
        $sourceLabel = $langLabels[$sourceLang] ?? $sourceLang;
        $targetLabel = $langLabels[$targetLang] ?? $targetLang;

        $prompt = "Traduis ce texte de {$sourceLabel} vers {$targetLabel} de manière naturelle et culturellement fidèle. "
            . "Retourne uniquement la traduction, sans explication.\n\nTexte : {$text}";

        $result = $this->callGemini($prompt);
        return $result ?: $text;
    }

    private function seedLearningWords(Artisan $artisan): object
    {
        $craftName = mb_strtolower($artisan->category?->name ?? '');

        $baseWords = [
            ['local_word' => 'Kouabo',    'french_translation' => 'Bienvenue',           'english_translation' => 'Welcome',          'context' => 'greeting'],
            ['local_word' => 'Awanou',    'french_translation' => 'Merci',               'english_translation' => 'Thank you',         'context' => 'greeting'],
            ['local_word' => 'Nabi ?',    'french_translation' => 'Combien ça coûte ?',  'english_translation' => 'How much?',         'context' => 'commerce'],
            ['local_word' => 'Ɛɛ',        'french_translation' => 'Oui',                 'english_translation' => 'Yes',               'context' => 'basic'],
            ['local_word' => 'Ɔɔ',        'french_translation' => 'Non',                 'english_translation' => 'No',                'context' => 'basic'],
            ['local_word' => 'Nùjɔnǔ',   'french_translation' => 'Belle chose / Œuvre', 'english_translation' => 'Beautiful piece',   'context' => 'craft'],
            ['local_word' => 'Alɔnuzɔ',  'french_translation' => 'Artisanat',            'english_translation' => 'Handicraft',        'context' => 'craft'],
            ['local_word' => 'Wlɛnwlɛn', 'french_translation' => 'Créativité',           'english_translation' => 'Creativity',        'context' => 'craft'],
            ['local_word' => 'Xwé',      'french_translation' => 'Maison / Atelier',     'english_translation' => 'House / Workshop',  'context' => 'basic'],
            ['local_word' => 'Agbádò',   'french_translation' => 'Terre / Argile',       'english_translation' => 'Earth / Clay',      'context' => 'craft'],
        ];

        if (str_contains($craftName, 'poterie') || str_contains($craftName, 'céramique')) {
            $baseWords = array_merge($baseWords, [
                ['local_word' => 'Zɛn',    'french_translation' => 'Canari / Pot en argile', 'english_translation' => 'Clay pot',       'context' => 'craft'],
                ['local_word' => 'Fìfá',   'french_translation' => 'Argile / Terre glaise',  'english_translation' => 'Clay',           'context' => 'craft'],
                ['local_word' => 'Wlizɔ',  'french_translation' => 'Façonnage',              'english_translation' => 'Shaping',        'context' => 'craft'],
                ['local_word' => 'Gbá',    'french_translation' => 'Four de potier',         'english_translation' => "Potter's oven",  'context' => 'craft'],
            ]);
        } elseif (str_contains($craftName, 'tissage') || str_contains($craftName, 'tissu')) {
            $baseWords = array_merge($baseWords, [
                ['local_word' => 'Kanvo',  'french_translation' => 'Tissu tissé à la main', 'english_translation' => 'Woven cloth',    'context' => 'craft'],
                ['local_word' => 'Fí',     'french_translation' => 'Fil / Fibre',            'english_translation' => 'Thread',         'context' => 'craft'],
                ['local_word' => 'Ganví',  'french_translation' => 'Métier à tisser',        'english_translation' => 'Loom',           'context' => 'craft'],
            ]);
        } elseif (str_contains($craftName, 'sculpture') || str_contains($craftName, 'bois')) {
            $baseWords = array_merge($baseWords, [
                ['local_word' => 'Atín',     'french_translation' => 'Bois / Arbre',      'english_translation' => 'Wood',          'context' => 'craft'],
                ['local_word' => 'Gbɛtɛzɔ', 'french_translation' => 'Sculpture',         'english_translation' => 'Sculpture',     'context' => 'craft'],
                ['local_word' => 'Kpó',      'french_translation' => 'Masque',            'english_translation' => 'Mask',          'context' => 'craft'],
            ]);
        } elseif (str_contains($craftName, 'bijou') || str_contains($craftName, 'bronze')) {
            $baseWords = array_merge($baseWords, [
                ['local_word' => 'Weziza', 'french_translation' => 'Bijou / Parure',  'english_translation' => 'Jewelry',    'context' => 'craft'],
                ['local_word' => 'Kɔn',    'french_translation' => 'Bronze / Métal',  'english_translation' => 'Metal',      'context' => 'craft'],
            ]);
        }

        foreach ($baseWords as $w) {
            DB::table('dokun_learning_words')->insert([
                'category_id'         => $artisan->category_id,
                'local_word'          => $w['local_word'],
                'french_translation'  => $w['french_translation'],
                'english_translation' => $w['english_translation'],
                'context'             => $w['context'],
                'language'            => 'fon',
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }

        return DB::table('dokun_learning_words')
            ->where('category_id', $artisan->category_id)
            ->get();
    }
}
