<?php

namespace Database\Seeders;

use App\Models\LearnCourse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LearnContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('learn_progress')->delete();
        DB::table('learn_words')->delete();
        DB::table('learn_lessons')->delete();
        DB::table('learn_courses')->delete();

        $courses = [
            [
                'slug' => 'salutations', 'icon' => 'hand-wave', 'accent' => '#064E3B',
                'title_fr' => 'Salutations & Politesse', 'title_en' => 'Greetings & Politeness',
                'desc_fr' => 'Dire bonjour, merci et se présenter en fon — la base de toute rencontre à Porto-Novo.',
                'desc_en' => 'Say hello, thank you and introduce yourself in Fon — the basis of every encounter in Porto-Novo.',
                'lessons' => [
                    ['slug' => 'premiers-mots', 'title_fr' => 'Les premiers mots', 'title_en' => 'First words', 'words' => [
                        ['Kouabo', 'Bienvenue', 'Welcome', 'greeting'],
                        ['Ɛɛ', 'Oui', 'Yes', 'basic'],
                        ['Ɔɔ', 'Non', 'No', 'basic'],
                        ['Awanu', 'Merci', 'Thank you', 'greeting'],
                        ['Ayɔɔ', 'Bonne journée', 'Good day', 'greeting'],
                    ]],
                    ['slug' => 'demander-nouvelles', 'title_fr' => 'Demander des nouvelles', 'title_en' => 'Asking for news', 'words' => [
                        ['Kú dó ?', 'Comment vas-tu ?', 'How are you?', 'greeting'],
                        ['Mi ɖɛ mɛ ?', 'Comment allez-vous ?', 'How are you (formal)?', 'greeting'],
                        ['Ɖokù ɖè', 'Ça va bien', 'I am fine', 'greeting'],
                        ['Nkɔ́ ɖè wɛ̀', 'Je vais bien, merci', 'I am well, thank you', 'greeting'],
                        ['Mà wǎ zó', 'À demain', 'See you tomorrow', 'greeting'],
                    ]],
                    ['slug' => 'se-presenter', 'title_fr' => 'Se présenter', 'title_en' => 'Introducing yourself', 'words' => [
                        ['Nyě', 'Moi / Je', 'Me / I', 'basic'],
                        ['Nò wè', 'Toi / Tu', 'You', 'basic'],
                        ['Ɖò tɔ́n nyí...', 'Mon nom est...', 'My name is...', 'greeting'],
                        ['Nì bì ɔ', 'Enchanté', 'Nice to meet you', 'greeting'],
                        ['Tàjí', 'S\'il te plaît', 'Please', 'politeness'],
                    ]],
                ],
            ],
            [
                'slug' => 'marche', 'icon' => 'basket', 'accent' => '#C99424',
                'title_fr' => 'Au Marché', 'title_en' => 'At the Market',
                'desc_fr' => 'Négocier, compter et acheter au marché Dantokpa ou aux marchés de Porto-Novo.',
                'desc_en' => 'Negotiate, count and buy at Dantokpa market or Porto-Novo markets.',
                'lessons' => [
                    ['slug' => 'compter', 'title_fr' => 'Compter de 1 à 10', 'title_en' => 'Counting 1 to 10', 'words' => [
                        ['Dekǔ', 'Un', 'One', 'number'],
                        ['Wě', 'Deux', 'Two', 'number'],
                        ['Atɔn', 'Trois', 'Three', 'number'],
                        ['Ɛnɛ', 'Quatre', 'Four', 'number'],
                        ['Atɔn nǔ déwú', 'Cinq', 'Five', 'number'],
                    ]],
                    ['slug' => 'negocier', 'title_fr' => 'Négocier le prix', 'title_en' => 'Negotiating the price', 'words' => [
                        ['Nabi ?', 'Combien ça coûte ?', 'How much is it?', 'commerce'],
                        ['È ɖò kàn...', 'Cela coûte...', 'It costs...', 'commerce'],
                        ['À jɛ nù jí', 'Réduis le prix !', 'Lower the price!', 'commerce'],
                        ['Dó bǐ', 'Tout / Le tout', 'Everything / All of it', 'commerce'],
                        ['Nú é nì', 'Pour toi', 'For you', 'commerce'],
                    ]],
                    ['slug' => 'acheter', 'title_fr' => 'Acheter & remercier', 'title_en' => 'Buying & thanking', 'words' => [
                        ['Nú wè ɔ', 'Voici pour toi', 'Here for you', 'commerce'],
                        ['Nì klɔ́n', 'Donne-moi', 'Give me', 'commerce'],
                        ['Xwè', 'Argent', 'Money', 'commerce'],
                        ['Awanu tawun', 'Merci beaucoup', 'Thank you very much', 'politeness'],
                        ['Hwè jɛ', 'C\'est cher', 'That is expensive', 'commerce'],
                    ]],
                ],
            ],
            [
                'slug' => 'atelier', 'icon' => 'tools', 'accent' => '#2563EB',
                'title_fr' => "Dans l'Atelier d'Artisan", 'title_en' => "In the Artisan's Workshop",
                'desc_fr' => 'Le vocabulaire des métiers : poterie, tissage Kanvo, sculpture et bronze.',
                'desc_en' => 'Craft vocabulary: pottery, Kanvo weaving, sculpture and bronze.',
                'lessons' => [
                    ['slug' => 'mots-atelier', 'title_fr' => "Les mots de l'atelier", 'title_en' => 'Workshop words', 'words' => [
                        ['Alɔnuzɔ', 'Artisanat', 'Handicraft', 'craft'],
                        ['Nùjɔnǔ', 'Belle chose / Œuvre', 'Beautiful piece', 'craft'],
                        ['Xwé', 'Maison / Atelier', 'House / Workshop', 'basic'],
                        ['Wlɛnwlɛn', 'Créativité', 'Creativity', 'craft'],
                        ['Azɔ̌', 'Travail', 'Work', 'craft'],
                    ]],
                    ['slug' => 'poterie-terre', 'title_fr' => 'Poterie & terre', 'title_en' => 'Pottery & clay', 'words' => [
                        ['Agbádò', 'Terre / Argile', 'Earth / Clay', 'craft'],
                        ['Zɛn', 'Canari / Pot en argile', 'Clay pot', 'craft'],
                        ['Fìfá', 'Terre glaise', 'Clay', 'craft'],
                        ['Wlizɔ', 'Façonnage', 'Shaping', 'craft'],
                        ['Gbá', 'Four de potier', "Potter's oven", 'craft'],
                    ]],
                    ['slug' => 'tissage-sculpture', 'title_fr' => 'Tissage & sculpture', 'title_en' => 'Weaving & sculpture', 'words' => [
                        ['Kanvo', 'Tissu tissé à la main', 'Woven cloth', 'craft'],
                        ['Ganví', 'Métier à tisser', 'Loom', 'craft'],
                        ['Atín', 'Bois / Arbre', 'Wood', 'craft'],
                        ['Gbɛtɛzɔ', 'Sculpture', 'Sculpture', 'craft'],
                        ['Kpó', 'Masque', 'Mask', 'craft'],
                    ]],
                ],
            ],
            [
                'slug' => 'en-ville', 'icon' => 'compass', 'accent' => '#17201D',
                'title_fr' => 'Se Repérer en Ville', 'title_en' => 'Getting Around Town',
                'desc_fr' => 'Demander son chemin, prendre un zémidjan et découvrir les lieux de Porto-Novo.',
                'desc_en' => 'Ask directions, take a zémidjan and discover Porto-Novo places.',
                'lessons' => [
                    ['slug' => 'lieux', 'title_fr' => 'Les lieux', 'title_en' => 'Places', 'words' => [
                        ['Tò', 'Ville / Pays', 'City / Country', 'place'],
                        ['Xwé mɛ', 'À la maison', 'At home', 'place'],
                        ['Asibǎ', 'Marché', 'Market', 'place'],
                        ['Wɛnɛxwé', 'Église', 'Church', 'place'],
                        ['Masazɔxwé', 'Hôpital', 'Hospital', 'place'],
                    ]],
                    ['slug' => 'directions', 'title_fr' => 'Demander le chemin', 'title_en' => 'Asking directions', 'words' => [
                        ['Nú ɖé ɖò fì é ? ', 'Où est-ce ?', 'Where is it?', 'direction'],
                    ]],
                ],
            ],
        ];

        foreach ($courses as $cIndex => $course) {
            $created = LearnCourse::create([
                'slug' => $course['slug'],
                'title_fr' => $course['title_fr'],
                'title_en' => $course['title_en'],
                'desc_fr' => $course['desc_fr'],
                'desc_en' => $course['desc_en'],
                'icon' => $course['icon'],
                'accent' => $course['accent'],
                'sort_order' => $cIndex,
            ]);

            foreach ($course['lessons'] as $lIndex => $lesson) {
                $createdLesson = $created->lessons()->create([
                    'slug' => $lesson['slug'],
                    'title_fr' => $lesson['title_fr'],
                    'title_en' => $lesson['title_en'],
                    'sort_order' => $lIndex,
                ]);

                foreach ($lesson['words'] as $wIndex => $word) {
                    $createdLesson->words()->create([
                        'local_word' => $word[0],
                        'french_translation' => $word[1],
                        'english_translation' => $word[2],
                        'context' => $word[3],
                        'sort_order' => $wIndex,
                    ]);
                }
            }
        }
    }
}
