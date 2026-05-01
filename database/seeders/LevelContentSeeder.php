<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use App\Models\Activity;
use App\Models\ActivitySubmission;
use App\Models\LevelProgress;
use App\Models\Progress;

class LevelContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        ActivitySubmission::truncate();
        LevelProgress::truncate();
        Progress::truncate();
        Activity::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $modules = Module::all()->keyBy('subject');

        $this->alphabet($modules['alphabet'] ?? null);
        $this->numbers($modules['numbers']  ?? null);
        $this->colors($modules['colors']    ?? null);
        $this->shapes($modules['shapes']    ?? null);
        $this->words($modules['words']      ?? null);
    }

    // ------------------------------------------------------------------ helpers
    private function act(Module $m, int $level, string $title, string $type, array $content, int $order, int $stars = 3): void
    {
        Activity::create([
            'module_id'    => $m->id,
            'level_number' => $level,
            'title'        => $title,
            'type'         => $type,
            'content'      => $content,
            'stars_reward' => $stars,
            'order'        => $order,
            'is_active'    => true,
        ]);
    }

    private function quiz(array $questions): array
    {
        return ['style' => 'default', 'questions' => $questions];
    }

    private function matching(array $pairs): array
    {
        $rights = array_column($pairs, 'right');
        $questions = [];
        foreach ($pairs as $pair) {
            $questions[] = [
                'text'    => $pair['left'],
                'options' => $rights,
                'correct' => $pair['right'],
            ];
        }
        return ['style' => 'matching', 'pairs' => $pairs, 'questions' => $questions];
    }

    private function wordBuilder(string $word, string $hint, array $extraLetters): array
    {
        $letters = array_merge(str_split($word), $extraLetters);
        shuffle($letters);
        return [
            'style'     => 'word_builder',
            'word'      => $word,
            'hint'      => $hint,
            'letters'   => $letters,
            'questions' => [[
                'text'    => "Spell the word for $hint",
                'options' => [$word],
                'correct' => $word,
            ]],
        ];
    }

    // ================================================================== ALPHABET
    private function alphabet(?Module $m): void
    {
        if (!$m) return;

        // Level 1 — Meet A B C D
        $this->act($m, 1, 'Letter Hunt A-D!', 'quiz', $this->quiz([
            ['text' => 'Which letter is A?',           'options' => ['A','B','C','D'], 'correct' => 'A'],
            ['text' => 'Which letter is B?',           'options' => ['A','B','C','D'], 'correct' => 'B'],
            ['text' => 'Which letter comes after A?',  'options' => ['B','C','D','E'], 'correct' => 'B'],
            ['text' => 'Which is the letter C?',       'options' => ['A','B','C','D'], 'correct' => 'C'],
            ['text' => 'Which is the letter D?',       'options' => ['P','Q','D','G'], 'correct' => 'D'],
        ]), 1, 3);

        $this->act($m, 1, 'Big & Small A-D', 'matching', $this->matching([
            ['left' => 'A', 'right' => 'a'],
            ['left' => 'B', 'right' => 'b'],
            ['left' => 'C', 'right' => 'c'],
            ['left' => 'D', 'right' => 'd'],
        ]), 2, 3);

        // Level 2 — Letters E F G H
        $this->act($m, 2, 'Letters E-H!', 'quiz', $this->quiz([
            ['text' => 'Which letter is E?',           'options' => ['E','F','G','H'], 'correct' => 'E'],
            ['text' => 'Which letter is F?',           'options' => ['E','F','G','H'], 'correct' => 'F'],
            ['text' => 'Which letter comes after F?',  'options' => ['E','G','H','I'], 'correct' => 'G'],
            ['text' => 'Which is the letter H?',       'options' => ['E','F','G','H'], 'correct' => 'H'],
            ['text' => '🥚 Egg starts with which letter?', 'options' => ['A','E','I','O'], 'correct' => 'E'],
        ]), 1, 3);

        $this->act($m, 2, 'Big & Small E-H', 'matching', $this->matching([
            ['left' => 'E', 'right' => 'e'],
            ['left' => 'F', 'right' => 'f'],
            ['left' => 'G', 'right' => 'g'],
            ['left' => 'H', 'right' => 'h'],
        ]), 2, 3);

        // Level 3 — Letter Sounds A-H
        $this->act($m, 3, 'First Letter Fun!', 'quiz', $this->quiz([
            ['text' => '🍎 Apple starts with?',   'options' => ['A','B','C','D'], 'correct' => 'A'],
            ['text' => '🐝 Bee starts with?',      'options' => ['A','B','C','D'], 'correct' => 'B'],
            ['text' => '🐱 Cat starts with?',      'options' => ['A','B','C','D'], 'correct' => 'C'],
            ['text' => '🐶 Dog starts with?',      'options' => ['A','B','C','D'], 'correct' => 'D'],
            ['text' => '🐘 Elephant starts with?', 'options' => ['D','E','F','G'], 'correct' => 'E'],
        ]), 1, 3);

        $this->act($m, 3, 'Spell CAT! 🐱', 'quiz',
            $this->wordBuilder('CAT', '🐱', ['B','X','M','S']), 2, 3);

        // Level 4 — Letters I J K L
        $this->act($m, 4, 'Letters I-L!', 'quiz', $this->quiz([
            ['text' => 'Which letter is I?',          'options' => ['I','J','K','L'], 'correct' => 'I'],
            ['text' => 'Which letter is J?',          'options' => ['I','J','K','L'], 'correct' => 'J'],
            ['text' => '🍦 Ice cream starts with?',   'options' => ['H','I','J','K'], 'correct' => 'I'],
            ['text' => '🦘 Kangaroo starts with?',    'options' => ['I','J','K','L'], 'correct' => 'K'],
            ['text' => '🦁 Lion starts with?',        'options' => ['I','J','K','L'], 'correct' => 'L'],
        ]), 1, 3);

        $this->act($m, 4, 'Big & Small I-L', 'matching', $this->matching([
            ['left' => 'I', 'right' => 'i'],
            ['left' => 'J', 'right' => 'j'],
            ['left' => 'K', 'right' => 'k'],
            ['left' => 'L', 'right' => 'l'],
        ]), 2, 3);

        // Level 5 — Letters M N O P
        $this->act($m, 5, 'Letters M-P!', 'quiz', $this->quiz([
            ['text' => '🐒 Monkey starts with?',   'options' => ['M','N','O','P'], 'correct' => 'M'],
            ['text' => '🌙 Night starts with?',    'options' => ['M','N','O','P'], 'correct' => 'N'],
            ['text' => '🐙 Octopus starts with?',  'options' => ['M','N','O','P'], 'correct' => 'O'],
            ['text' => '🐷 Pig starts with?',      'options' => ['M','N','O','P'], 'correct' => 'P'],
            ['text' => 'Which letter comes after N?','options' => ['M','O','P','Q'], 'correct' => 'O'],
        ]), 1, 3);

        $this->act($m, 5, 'Spell PIG! 🐷', 'quiz',
            $this->wordBuilder('PIG', '🐷', ['A','N','T','S']), 2, 3);

        // Level 6 — Letters Q R S T
        $this->act($m, 6, 'Letters Q-T!', 'quiz', $this->quiz([
            ['text' => '👑 Queen starts with?',    'options' => ['Q','R','S','T'], 'correct' => 'Q'],
            ['text' => '🌈 Rainbow starts with?',  'options' => ['Q','R','S','T'], 'correct' => 'R'],
            ['text' => '☀️ Sun starts with?',       'options' => ['Q','R','S','T'], 'correct' => 'S'],
            ['text' => '🐯 Tiger starts with?',    'options' => ['Q','R','S','T'], 'correct' => 'T'],
            ['text' => 'Which letter comes after R?','options' => ['Q','S','T','U'], 'correct' => 'S'],
        ]), 1, 3);

        $this->act($m, 6, 'Big & Small Q-T', 'matching', $this->matching([
            ['left' => 'Q', 'right' => 'q'],
            ['left' => 'R', 'right' => 'r'],
            ['left' => 'S', 'right' => 's'],
            ['left' => 'T', 'right' => 't'],
        ]), 2, 3);

        // Level 7 — Letters U V W X Y Z
        $this->act($m, 7, 'Letters U-Z!', 'quiz', $this->quiz([
            ['text' => '☂️ Umbrella starts with?', 'options' => ['U','V','W','X'], 'correct' => 'U'],
            ['text' => '🦄 Unicorn starts with?',  'options' => ['U','V','W','X'], 'correct' => 'U'],
            ['text' => '🐋 Whale starts with?',    'options' => ['V','W','X','Y'], 'correct' => 'W'],
            ['text' => '🧶 Yarn starts with?',     'options' => ['W','X','Y','Z'], 'correct' => 'Y'],
            ['text' => '🦓 Zebra starts with?',    'options' => ['X','Y','Z','A'], 'correct' => 'Z'],
        ]), 1, 3);

        $this->act($m, 7, 'Spell HEN! 🐔', 'quiz',
            $this->wordBuilder('HEN', '🐔', ['A','B','T','X']), 2, 3);

        // Level 8 — Alphabet Order
        $this->act($m, 8, 'ABC Order!', 'quiz', $this->quiz([
            ['text' => 'What comes after D?',   'options' => ['C','E','F','G'], 'correct' => 'E'],
            ['text' => 'What comes after J?',   'options' => ['I','K','L','M'], 'correct' => 'K'],
            ['text' => 'What comes after O?',   'options' => ['N','P','Q','R'], 'correct' => 'P'],
            ['text' => 'What comes after U?',   'options' => ['T','V','W','X'], 'correct' => 'V'],
            ['text' => 'What is the last letter?','options' => ['X','Y','Z','W'], 'correct' => 'Z'],
        ]), 1, 3);

        $this->act($m, 8, 'Big & Small U-Z', 'matching', $this->matching([
            ['left' => 'U', 'right' => 'u'],
            ['left' => 'V', 'right' => 'v'],
            ['left' => 'W', 'right' => 'w'],
            ['left' => 'Z', 'right' => 'z'],
        ]), 2, 3);

        // Level 9 — Words & Pictures
        $this->act($m, 9, 'Word Match!', 'quiz', $this->quiz([
            ['text' => '🌞 is called…',  'options' => ['Sun','Run','Fun','Gun'],          'correct' => 'Sun'],
            ['text' => '🐸 is called…',  'options' => ['Cat','Frog','Dog','Bird'],        'correct' => 'Frog'],
            ['text' => '🍎 is called…',  'options' => ['Orange','Apple','Mango','Grape'], 'correct' => 'Apple'],
            ['text' => '🌙 is called…',  'options' => ['Star','Sun','Cloud','Moon'],      'correct' => 'Moon'],
            ['text' => '🌸 is called…',  'options' => ['Leaf','Flower','Tree','Root'],    'correct' => 'Flower'],
        ]), 1, 3);

        $this->act($m, 9, 'Spell BUS! 🚌', 'quiz',
            $this->wordBuilder('BUS', '🚌', ['A','T','O','X']), 2, 3);

        // Level 10 — Alphabet Master
        $this->act($m, 10, 'Alphabet Champion!', 'quiz', $this->quiz([
            ['text' => 'How many letters are in the alphabet?', 'options' => ['24','25','26','27'], 'correct' => '26'],
            ['text' => 'What is the first letter?',             'options' => ['A','B','C','Z'],     'correct' => 'A'],
            ['text' => 'What is the last letter?',              'options' => ['X','Y','Z','W'],     'correct' => 'Z'],
            ['text' => '🦊 Fox starts with?',                  'options' => ['D','E','F','G'],     'correct' => 'F'],
            ['text' => '🐬 Dolphin starts with?',              'options' => ['A','B','C','D'],     'correct' => 'D'],
            ['text' => '🌴 Tree starts with?',                 'options' => ['R','S','T','U'],     'correct' => 'T'],
        ]), 1, 3);

        $this->act($m, 10, 'Spell FOX! 🦊', 'quiz',
            $this->wordBuilder('FOX', '🦊', ['A','B','C','D']), 2, 3);
    }

    // ================================================================== NUMBERS
    private function numbers(?Module $m): void
    {
        if (!$m) return;

        // Level 1 — Count to 5
        $this->act($m, 1, 'Count to 5!', 'quiz', $this->quiz([
            ['text' => 'How many? 🍎🍎🍎',         'options' => ['2','3','4','5'], 'correct' => '3'],
            ['text' => 'How many? ⭐⭐',             'options' => ['1','2','3','4'], 'correct' => '2'],
            ['text' => 'How many? 🐱🐱🐱🐱',       'options' => ['3','4','5','6'], 'correct' => '4'],
            ['text' => 'How many? 🌸🌸🌸🌸🌸',     'options' => ['4','5','6','7'], 'correct' => '5'],
            ['text' => 'How many? 🐶',              'options' => ['1','2','3','4'], 'correct' => '1'],
        ]), 1, 3);

        $this->act($m, 1, 'Number Match 1-5!', 'matching', $this->matching([
            ['left' => '1️⃣', 'right' => 'One'],
            ['left' => '2️⃣', 'right' => 'Two'],
            ['left' => '3️⃣', 'right' => 'Three'],
            ['left' => '4️⃣', 'right' => 'Four'],
        ]), 2, 3);

        // Level 2 — Number Order 1-5
        $this->act($m, 2, 'Number Order!', 'quiz', $this->quiz([
            ['text' => 'What number comes after 2?',  'options' => ['1','2','3','4'], 'correct' => '3'],
            ['text' => 'What number comes before 4?', 'options' => ['2','3','4','5'], 'correct' => '3'],
            ['text' => 'Which is the BIGGEST?',       'options' => ['1','3','5','2'], 'correct' => '5'],
            ['text' => 'Which is the SMALLEST?',      'options' => ['5','4','2','8'], 'correct' => '2'],
            ['text' => 'What comes after 5?',         'options' => ['4','5','6','7'], 'correct' => '6'],
        ]), 1, 3);

        $this->act($m, 2, 'Number Match 5-10!', 'matching', $this->matching([
            ['left' => '5️⃣', 'right' => 'Five'],
            ['left' => '6️⃣', 'right' => 'Six'],
            ['left' => '7️⃣', 'right' => 'Seven'],
            ['left' => '8️⃣', 'right' => 'Eight'],
        ]), 2, 3);

        // Level 3 — Count 6-10
        $this->act($m, 3, 'Count 6 to 10!', 'quiz', $this->quiz([
            ['text' => 'How many? 🌟🌟🌟🌟🌟🌟',           'options' => ['5','6','7','8'],  'correct' => '6'],
            ['text' => 'How many? 🍊🍊🍊🍊🍊🍊🍊',         'options' => ['6','7','8','9'],  'correct' => '7'],
            ['text' => 'How many? 🐝🐝🐝🐝🐝🐝🐝🐝',       'options' => ['7','8','9','10'], 'correct' => '8'],
            ['text' => 'How many? 🦋🦋🦋🦋🦋🦋🦋🦋🦋',     'options' => ['8','9','10','11'],'correct' => '9'],
            ['text' => 'How many? 🍕🍕🍕🍕🍕🍕🍕🍕🍕🍕', 'options' => ['8','9','10','11'],'correct' => '10'],
        ]), 1, 3);

        $this->act($m, 3, 'More or Less?', 'quiz', $this->quiz([
            ['text' => 'Is 7 bigger than 5?',  'options' => ['Yes','No'], 'correct' => 'Yes'],
            ['text' => 'Is 3 smaller than 9?', 'options' => ['Yes','No'], 'correct' => 'Yes'],
            ['text' => 'Is 10 bigger than 6?', 'options' => ['Yes','No'], 'correct' => 'Yes'],
            ['text' => 'Is 4 bigger than 8?',  'options' => ['Yes','No'], 'correct' => 'No'],
        ]), 2, 3);

        // Level 4 — Adding up to 5
        $this->act($m, 4, 'Simple Adding! ➕', 'quiz', $this->quiz([
            ['text' => '1 + 1 = ?',            'options' => ['1','2','3','4'], 'correct' => '2'],
            ['text' => '2 + 1 = ?',            'options' => ['2','3','4','5'], 'correct' => '3'],
            ['text' => '2 + 2 = ?',            'options' => ['3','4','5','6'], 'correct' => '4'],
            ['text' => '3 + 2 = ?',            'options' => ['4','5','6','7'], 'correct' => '5'],
            ['text' => '🍎🍎 + 🍎 = ?',       'options' => ['2','3','4','5'], 'correct' => '3'],
        ]), 1, 3);

        $this->act($m, 4, 'Adding Pictures!', 'quiz', $this->quiz([
            ['text' => '⭐⭐ + ⭐⭐ = ?',       'options' => ['3','4','5','6'], 'correct' => '4'],
            ['text' => '🐱 + 🐱🐱 = ?',       'options' => ['2','3','4','5'], 'correct' => '3'],
            ['text' => '🌸🌸🌸 + 🌸 = ?',     'options' => ['3','4','5','6'], 'correct' => '4'],
            ['text' => '🍕🍕 + 🍕🍕🍕 = ?',   'options' => ['4','5','6','7'], 'correct' => '5'],
        ]), 2, 3);

        // Level 5 — Subtracting up to 5
        $this->act($m, 5, 'Take Away! ➖', 'quiz', $this->quiz([
            ['text' => '2 - 1 = ?',                    'options' => ['0','1','2','3'], 'correct' => '1'],
            ['text' => '3 - 1 = ?',                    'options' => ['1','2','3','4'], 'correct' => '2'],
            ['text' => '4 - 2 = ?',                    'options' => ['1','2','3','4'], 'correct' => '2'],
            ['text' => '5 - 3 = ?',                    'options' => ['1','2','3','4'], 'correct' => '2'],
            ['text' => '🍎🍎🍎 take away 🍎 = ?',    'options' => ['1','2','3','4'], 'correct' => '2'],
        ]), 1, 3);

        $this->act($m, 5, 'Number Match!', 'matching', $this->matching([
            ['left' => 'Nine',  'right' => '9'],
            ['left' => 'Ten',   'right' => '10'],
            ['left' => 'Seven', 'right' => '7'],
            ['left' => 'Eight', 'right' => '8'],
        ]), 2, 3);

        // Level 6 — Adding up to 10
        $this->act($m, 6, 'Add to 10! ➕', 'quiz', $this->quiz([
            ['text' => '3 + 3 = ?',   'options' => ['5','6','7','8'], 'correct' => '6'],
            ['text' => '4 + 3 = ?',   'options' => ['6','7','8','9'], 'correct' => '7'],
            ['text' => '5 + 3 = ?',   'options' => ['7','8','9','10'],'correct' => '8'],
            ['text' => '4 + 5 = ?',   'options' => ['7','8','9','10'],'correct' => '9'],
            ['text' => '5 + 5 = ?',   'options' => ['8','9','10','11'],'correct' => '10'],
        ]), 1, 3);

        $this->act($m, 6, 'Subtract from 10! ➖', 'quiz', $this->quiz([
            ['text' => '5 - 2 = ?',   'options' => ['2','3','4','5'], 'correct' => '3'],
            ['text' => '8 - 4 = ?',   'options' => ['3','4','5','6'], 'correct' => '4'],
            ['text' => '10 - 5 = ?',  'options' => ['4','5','6','7'], 'correct' => '5'],
            ['text' => '9 - 3 = ?',   'options' => ['5','6','7','8'], 'correct' => '6'],
            ['text' => '10 - 4 = ?',  'options' => ['5','6','7','8'], 'correct' => '6'],
        ]), 2, 3);

        // Level 7 — Count 11-15
        $this->act($m, 7, 'Count 11-15!', 'quiz', $this->quiz([
            ['text' => 'What comes after 10?',  'options' => ['9','11','12','13'],  'correct' => '11'],
            ['text' => 'What comes after 12?',  'options' => ['11','13','14','15'], 'correct' => '13'],
            ['text' => 'Which is bigger: 11 or 14?', 'options' => ['11','14'],     'correct' => '14'],
            ['text' => 'Which is smaller: 13 or 15?','options' => ['13','15'],     'correct' => '13'],
            ['text' => 'What comes before 15?', 'options' => ['12','13','14','16'],'correct' => '14'],
        ]), 1, 3);

        $this->act($m, 7, 'Number Words 11-15', 'matching', $this->matching([
            ['left' => 'Eleven',   'right' => '11'],
            ['left' => 'Twelve',   'right' => '12'],
            ['left' => 'Thirteen', 'right' => '13'],
            ['left' => 'Fourteen', 'right' => '14'],
        ]), 2, 3);

        // Level 8 — Count 16-20
        $this->act($m, 8, 'Count 16-20!', 'quiz', $this->quiz([
            ['text' => 'What comes after 15?',   'options' => ['14','16','17','18'], 'correct' => '16'],
            ['text' => 'What comes after 17?',   'options' => ['16','18','19','20'], 'correct' => '18'],
            ['text' => 'What comes before 20?',  'options' => ['17','18','19','21'], 'correct' => '19'],
            ['text' => 'Which is bigger: 16 or 19?', 'options' => ['16','19'],      'correct' => '19'],
            ['text' => 'How many tens in 20?',   'options' => ['1','2','3','4'],    'correct' => '2'],
        ]), 1, 3);

        $this->act($m, 8, 'Number Words 16-20', 'matching', $this->matching([
            ['left' => 'Fifteen',  'right' => '15'],
            ['left' => 'Sixteen',  'right' => '16'],
            ['left' => 'Eighteen', 'right' => '18'],
            ['left' => 'Twenty',   'right' => '20'],
        ]), 2, 3);

        // Level 9 — Odd and Even
        $this->act($m, 9, 'Odd or Even?', 'quiz', $this->quiz([
            ['text' => 'Is 2 odd or even?',  'options' => ['Odd','Even'], 'correct' => 'Even'],
            ['text' => 'Is 3 odd or even?',  'options' => ['Odd','Even'], 'correct' => 'Odd'],
            ['text' => 'Is 6 odd or even?',  'options' => ['Odd','Even'], 'correct' => 'Even'],
            ['text' => 'Is 7 odd or even?',  'options' => ['Odd','Even'], 'correct' => 'Odd'],
            ['text' => 'Is 10 odd or even?', 'options' => ['Odd','Even'], 'correct' => 'Even'],
        ]), 1, 3);

        $this->act($m, 9, 'Skip Count by 2s!', 'quiz', $this->quiz([
            ['text' => '2, 4, ?, 8',    'options' => ['5','6','7','8'],  'correct' => '6'],
            ['text' => '4, 6, 8, ?',    'options' => ['9','10','11','12'],'correct' => '10'],
            ['text' => '10, 12, ?, 16', 'options' => ['13','14','15','16'],'correct' => '14'],
            ['text' => '2, ?, 6, 8',    'options' => ['3','4','5','6'],  'correct' => '4'],
        ]), 2, 3);

        // Level 10 — Number Champion
        $this->act($m, 10, 'Math Champion! 🏆', 'quiz', $this->quiz([
            ['text' => '6 + 7 = ?',              'options' => ['12','13','14','15'], 'correct' => '13'],
            ['text' => '15 - 6 = ?',             'options' => ['7','8','9','10'],   'correct' => '9'],
            ['text' => 'What is 5 + 5 + 5?',     'options' => ['10','12','15','20'],'correct' => '15'],
            ['text' => 'Which is even: 11 or 12?','options' => ['11','12'],          'correct' => '12'],
            ['text' => 'What comes after 19?',    'options' => ['18','20','21','22'],'correct' => '20'],
            ['text' => '20 - 10 = ?',             'options' => ['5','8','10','12'], 'correct' => '10'],
        ]), 1, 3);

        $this->act($m, 10, 'Number Master Match!', 'matching', $this->matching([
            ['left' => 'Nineteen', 'right' => '19'],
            ['left' => 'Twenty',   'right' => '20'],
            ['left' => 'Fifteen',  'right' => '15'],
            ['left' => 'Seventeen','right' => '17'],
        ]), 2, 3);
    }

    // ================================================================== COLORS
    private function colors(?Module $m): void
    {
        if (!$m) return;

        // Level 1 — Basic Colors
        $this->act($m, 1, 'Name That Color!', 'quiz', $this->quiz([
            ['text' => '🔴 This is the color…', 'options' => ['Red','Blue','Green','Yellow'],  'correct' => 'Red'],
            ['text' => '🔵 This is the color…', 'options' => ['Red','Blue','Green','Yellow'],  'correct' => 'Blue'],
            ['text' => '🟡 This is the color…', 'options' => ['Red','Purple','Green','Yellow'],'correct' => 'Yellow'],
            ['text' => '🟢 This is the color…', 'options' => ['Red','Blue','Green','Pink'],    'correct' => 'Green'],
            ['text' => '🟣 This is the color…', 'options' => ['Orange','Purple','Brown','Pink'],'correct' => 'Purple'],
        ]), 1, 3);

        $this->act($m, 1, 'Color Match!', 'matching', $this->matching([
            ['left' => '🔴', 'right' => 'Red'],
            ['left' => '🔵', 'right' => 'Blue'],
            ['left' => '🟡', 'right' => 'Yellow'],
            ['left' => '🟢', 'right' => 'Green'],
        ]), 2, 3);

        // Level 2 — More Colors
        $this->act($m, 2, 'More Colors!', 'quiz', $this->quiz([
            ['text' => '🟠 This is the color…', 'options' => ['Orange','Red','Pink','Brown'],   'correct' => 'Orange'],
            ['text' => '⚫ This is the color…', 'options' => ['Gray','White','Black','Blue'],   'correct' => 'Black'],
            ['text' => '⬜ This is the color…', 'options' => ['Gray','White','Black','Yellow'], 'correct' => 'White'],
            ['text' => '🟤 This is the color…', 'options' => ['Orange','Red','Pink','Brown'],   'correct' => 'Brown'],
            ['text' => '🩷 This is the color…', 'options' => ['Orange','Purple','Pink','Red'],  'correct' => 'Pink'],
        ]), 1, 3);

        $this->act($m, 2, 'More Color Match!', 'matching', $this->matching([
            ['left' => '🟠', 'right' => 'Orange'],
            ['left' => '⚫', 'right' => 'Black'],
            ['left' => '⬜', 'right' => 'White'],
            ['left' => '🟤', 'right' => 'Brown'],
        ]), 2, 3);

        // Level 3 — Colors in the World
        $this->act($m, 3, 'What Color is it?', 'quiz', $this->quiz([
            ['text' => 'What color is the sky? 🌤',  'options' => ['Blue','Green','Red','Yellow'],   'correct' => 'Blue'],
            ['text' => 'What color is grass? 🌿',    'options' => ['Brown','Green','Blue','Yellow'], 'correct' => 'Green'],
            ['text' => 'What color is the sun? ☀️',  'options' => ['White','Red','Yellow','Orange'], 'correct' => 'Yellow'],
            ['text' => 'What color is a banana? 🍌', 'options' => ['Red','Yellow','Green','Blue'],   'correct' => 'Yellow'],
            ['text' => 'What color is snow? ❄️',     'options' => ['Blue','White','Gray','Yellow'],  'correct' => 'White'],
        ]), 1, 3);

        $this->act($m, 3, 'Animal Colors!', 'matching', $this->matching([
            ['left' => '🐧 Penguin',  'right' => 'Black & White'],
            ['left' => '🦊 Fox',      'right' => 'Orange'],
            ['left' => '🐸 Frog',     'right' => 'Green'],
            ['left' => '🦩 Flamingo', 'right' => 'Pink'],
        ]), 2, 3);

        // Level 4 — Color Mixing
        $this->act($m, 4, 'Color Mixing! 🎨', 'quiz', $this->quiz([
            ['text' => '🔴 Red + 🔵 Blue = ?',   'options' => ['Green','Purple','Orange','Pink'],  'correct' => 'Purple'],
            ['text' => '🔴 Red + 🟡 Yellow = ?', 'options' => ['Purple','Orange','Blue','Green'],  'correct' => 'Orange'],
            ['text' => '🔵 Blue + 🟡 Yellow = ?','options' => ['Purple','Orange','Green','Red'],   'correct' => 'Green'],
            ['text' => '🔴 Red + ⬜ White = ?',  'options' => ['Purple','Pink','Brown','Gray'],    'correct' => 'Pink'],
            ['text' => '⚫ Black + ⬜ White = ?', 'options' => ['Blue','Brown','Gray','Purple'],   'correct' => 'Gray'],
        ]), 1, 3);

        $this->act($m, 4, 'Color Mix Match!', 'matching', $this->matching([
            ['left' => 'Red + Blue',   'right' => 'Purple'],
            ['left' => 'Red + Yellow', 'right' => 'Orange'],
            ['left' => 'Blue + Yellow','right' => 'Green'],
            ['left' => 'Black + White','right' => 'Gray'],
        ]), 2, 3);

        // Level 5 — Fruit Colors
        $this->act($m, 5, 'Fruit Colors! 🍓', 'quiz', $this->quiz([
            ['text' => 'What color is a strawberry? 🍓', 'options' => ['Red','Blue','Yellow','Green'],  'correct' => 'Red'],
            ['text' => 'What color is an orange? 🍊',    'options' => ['Red','Orange','Yellow','Green'], 'correct' => 'Orange'],
            ['text' => 'What color is a grape? 🍇',      'options' => ['Red','Pink','Purple','Blue'],   'correct' => 'Purple'],
            ['text' => 'What color is a lime? 🟢',       'options' => ['Yellow','Green','Orange','Red'], 'correct' => 'Green'],
            ['text' => 'What color is a blueberry? 🫐',  'options' => ['Purple','Blue','Black','Green'],'correct' => 'Blue'],
        ]), 1, 3);

        $this->act($m, 5, 'Fruit Color Match!', 'matching', $this->matching([
            ['left' => '🍎 Apple',    'right' => 'Red'],
            ['left' => '🍋 Lemon',    'right' => 'Yellow'],
            ['left' => '🍇 Grapes',   'right' => 'Purple'],
            ['left' => '🍊 Orange',   'right' => 'Orange'],
        ]), 2, 3);

        // Level 6 — Vehicle Colors
        $this->act($m, 6, 'Vehicle Colors! 🚗', 'quiz', $this->quiz([
            ['text' => 'What color is a fire truck? 🚒', 'options' => ['Blue','Red','Yellow','Green'], 'correct' => 'Red'],
            ['text' => 'What color is a school bus? 🚌', 'options' => ['Red','Blue','Yellow','Green'], 'correct' => 'Yellow'],
            ['text' => 'What color is a police car? 🚓', 'options' => ['Black & White','Red','Yellow','Green'],'correct' => 'Black & White'],
            ['text' => 'What color is a taxi? 🚕',       'options' => ['Red','Blue','Yellow','Green'], 'correct' => 'Yellow'],
        ]), 1, 3);

        $this->act($m, 6, 'Nature Color Match!', 'matching', $this->matching([
            ['left' => '🌊 Ocean',   'right' => 'Blue'],
            ['left' => '🌿 Leaves',  'right' => 'Green'],
            ['left' => '🌙 Moon',    'right' => 'White'],
            ['left' => '☁️ Cloud',   'right' => 'White'],
        ]), 2, 3);

        // Level 7 — Rainbow Order
        $this->act($m, 7, 'Rainbow Colors! 🌈', 'quiz', $this->quiz([
            ['text' => 'What is the first color of the rainbow?', 'options' => ['Red','Orange','Yellow','Blue'],'correct' => 'Red'],
            ['text' => 'What color comes after Red in the rainbow?','options' => ['Yellow','Orange','Blue','Green'],'correct' => 'Orange'],
            ['text' => 'What color comes after Orange in the rainbow?','options' => ['Red','Yellow','Blue','Green'],'correct' => 'Yellow'],
            ['text' => 'How many colors are in a rainbow?',       'options' => ['5','6','7','8'], 'correct' => '7'],
            ['text' => 'What is the last color of the rainbow?',  'options' => ['Blue','Purple','Violet','Pink'],'correct' => 'Violet'],
        ]), 1, 3);

        $this->act($m, 7, 'Rainbow Order Match!', 'matching', $this->matching([
            ['left' => '1st Rainbow Color', 'right' => 'Red'],
            ['left' => '2nd Rainbow Color', 'right' => 'Orange'],
            ['left' => '3rd Rainbow Color', 'right' => 'Yellow'],
            ['left' => '4th Rainbow Color', 'right' => 'Green'],
        ]), 2, 3);

        // Level 8 — Light & Dark
        $this->act($m, 8, 'Light or Dark?', 'quiz', $this->quiz([
            ['text' => 'Is White light or dark?',  'options' => ['Light','Dark'], 'correct' => 'Light'],
            ['text' => 'Is Black light or dark?',  'options' => ['Light','Dark'], 'correct' => 'Dark'],
            ['text' => 'Is Yellow light or dark?', 'options' => ['Light','Dark'], 'correct' => 'Light'],
            ['text' => 'Is Navy Blue light or dark?','options' => ['Light','Dark'],'correct' => 'Dark'],
            ['text' => 'Is Pink light or dark?',   'options' => ['Light','Dark'], 'correct' => 'Light'],
        ]), 1, 3);

        $this->act($m, 8, 'Clothes Color Match!', 'matching', $this->matching([
            ['left' => '👒 Hat',    'right' => 'Yellow'],
            ['left' => '👟 Shoes',  'right' => 'White'],
            ['left' => '🧣 Scarf',  'right' => 'Red'],
            ['left' => '🧤 Gloves', 'right' => 'Brown'],
        ]), 2, 3);

        // Level 9 — Classroom Colors
        $this->act($m, 9, 'Classroom Colors! 🏫', 'quiz', $this->quiz([
            ['text' => 'What color is a chalkboard? 🖤', 'options' => ['Black','Green','White','Blue'], 'correct' => 'Black'],
            ['text' => 'What color is a pencil? ✏️',     'options' => ['Yellow','Red','Blue','Green'],  'correct' => 'Yellow'],
            ['text' => 'What color is an eraser? 🩷',    'options' => ['Blue','Yellow','Pink','White'], 'correct' => 'Pink'],
            ['text' => 'What color is paper? 📄',        'options' => ['Yellow','Blue','White','Gray'], 'correct' => 'White'],
            ['text' => 'What color are most rulers? 📏', 'options' => ['Yellow','Red','Blue','Green'],  'correct' => 'Yellow'],
        ]), 1, 3);

        $this->act($m, 9, 'Color Word Match!', 'matching', $this->matching([
            ['left' => 'Rojo (Spanish)',   'right' => 'Red'],
            ['left' => 'Azul (Spanish)',   'right' => 'Blue'],
            ['left' => 'Verde (Spanish)',  'right' => 'Green'],
            ['left' => 'Amarillo (Spanish)','right' => 'Yellow'],
        ]), 2, 3);

        // Level 10 — Color Champion
        $this->act($m, 10, 'Color Champion! 🏆', 'quiz', $this->quiz([
            ['text' => 'What color do Red + Blue make?',   'options' => ['Orange','Green','Purple','Pink'], 'correct' => 'Purple'],
            ['text' => 'What color is a pumpkin? 🎃',      'options' => ['Red','Yellow','Orange','Purple'], 'correct' => 'Orange'],
            ['text' => 'What is the 5th rainbow color?',   'options' => ['Blue','Indigo','Green','Violet'], 'correct' => 'Blue'],
            ['text' => 'What color is a polar bear? 🐻‍❄️', 'options' => ['Gray','Yellow','White','Cream'],  'correct' => 'White'],
            ['text' => 'What color is chocolate? 🍫',      'options' => ['Black','Red','Brown','Orange'],   'correct' => 'Brown'],
            ['text' => 'Red + White makes which color?',   'options' => ['Purple','Pink','Orange','Peach'], 'correct' => 'Pink'],
        ]), 1, 3);

        $this->act($m, 10, 'Color Master Match!', 'matching', $this->matching([
            ['left' => '🍫 Chocolate', 'right' => 'Brown'],
            ['left' => '🍋 Lemon',     'right' => 'Yellow'],
            ['left' => '🍆 Eggplant',  'right' => 'Purple'],
            ['left' => '🌿 Grass',     'right' => 'Green'],
        ]), 2, 3);
    }

    // ================================================================== SHAPES
    private function shapes(?Module $m): void
    {
        if (!$m) return;

        // Level 1 — Basic Shapes
        $this->act($m, 1, 'What Shape is This?', 'quiz', $this->quiz([
            ['text' => '⬛ What shape is this?', 'options' => ['Circle','Square','Triangle','Rectangle'], 'correct' => 'Square'],
            ['text' => '🔺 What shape is this?', 'options' => ['Square','Circle','Triangle','Star'],     'correct' => 'Triangle'],
            ['text' => '⭕ What shape is this?', 'options' => ['Circle','Square','Triangle','Rectangle'], 'correct' => 'Circle'],
            ['text' => '▬ What shape is this?',  'options' => ['Square','Diamond','Rectangle','Oval'],   'correct' => 'Rectangle'],
            ['text' => '⋄ What shape is this?',  'options' => ['Circle','Square','Triangle','Diamond'],  'correct' => 'Diamond'],
        ]), 1, 3);

        $this->act($m, 1, 'Shape Match!', 'matching', $this->matching([
            ['left' => '⬛', 'right' => 'Square'],
            ['left' => '🔺', 'right' => 'Triangle'],
            ['left' => '⭕', 'right' => 'Circle'],
            ['left' => '▬',  'right' => 'Rectangle'],
        ]), 2, 3);

        // Level 2 — Count the Sides
        $this->act($m, 2, 'How Many Sides?', 'quiz', $this->quiz([
            ['text' => 'How many sides does a triangle have?',  'options' => ['2','3','4','5'], 'correct' => '3'],
            ['text' => 'How many sides does a square have?',    'options' => ['3','4','5','6'], 'correct' => '4'],
            ['text' => 'How many sides does a circle have?',    'options' => ['0','1','2','3'], 'correct' => '0'],
            ['text' => 'How many sides does a rectangle have?', 'options' => ['3','4','5','6'], 'correct' => '4'],
            ['text' => 'How many sides does a pentagon have?',  'options' => ['4','5','6','7'], 'correct' => '5'],
        ]), 1, 3);

        $this->act($m, 2, 'Shape Detective!', 'quiz', $this->quiz([
            ['text' => 'Which shape has NO corners?',    'options' => ['Square','Triangle','Circle','Rectangle'], 'correct' => 'Circle'],
            ['text' => 'Which shape has 3 sides?',       'options' => ['Square','Triangle','Circle','Rectangle'], 'correct' => 'Triangle'],
            ['text' => 'Which shape has 4 equal sides?', 'options' => ['Rectangle','Triangle','Square','Circle'], 'correct' => 'Square'],
            ['text' => 'Which shape has 5 sides?',       'options' => ['Hexagon','Pentagon','Octagon','Circle'],  'correct' => 'Pentagon'],
        ]), 2, 3);

        // Level 3 — Shapes in Real Life
        $this->act($m, 3, 'Shapes in Real Life!', 'quiz', $this->quiz([
            ['text' => 'A pizza 🍕 is shaped like a…',   'options' => ['Square','Circle','Triangle','Rectangle'], 'correct' => 'Circle'],
            ['text' => 'A door 🚪 is shaped like a…',    'options' => ['Circle','Square','Triangle','Rectangle'], 'correct' => 'Rectangle'],
            ['text' => 'A pizza slice 🍕 looks like a…', 'options' => ['Circle','Square','Triangle','Rectangle'], 'correct' => 'Triangle'],
            ['text' => 'A clock ⏰ is shaped like a…',   'options' => ['Square','Circle','Triangle','Rectangle'], 'correct' => 'Circle'],
            ['text' => 'A book 📚 is shaped like a…',    'options' => ['Circle','Triangle','Rectangle','Oval'],   'correct' => 'Rectangle'],
        ]), 1, 3);

        $this->act($m, 3, 'Object to Shape!', 'matching', $this->matching([
            ['left' => '🌍 Earth',   'right' => 'Circle'],
            ['left' => '🏔 Mountain','right' => 'Triangle'],
            ['left' => '📺 TV',      'right' => 'Rectangle'],
            ['left' => '🪟 Window',  'right' => 'Square'],
        ]), 2, 3);

        // Level 4 — New Shapes
        $this->act($m, 4, 'New Shapes!', 'quiz', $this->quiz([
            ['text' => 'Which shape has 6 sides?',      'options' => ['Pentagon','Hexagon','Octagon','Square'], 'correct' => 'Hexagon'],
            ['text' => 'Which shape has 8 sides?',      'options' => ['Pentagon','Hexagon','Octagon','Square'], 'correct' => 'Octagon'],
            ['text' => 'An egg 🥚 is shaped like an…', 'options' => ['Circle','Oval','Square','Diamond'],      'correct' => 'Oval'],
            ['text' => 'A stop sign has how many sides?','options' => ['6','7','8','9'],                        'correct' => '8'],
            ['text' => 'A honeycomb is made of which shape?','options' => ['Square','Triangle','Hexagon','Circle'],'correct' => 'Hexagon'],
        ]), 1, 3);

        $this->act($m, 4, 'New Shape Match!', 'matching', $this->matching([
            ['left' => '5 sides', 'right' => 'Pentagon'],
            ['left' => '6 sides', 'right' => 'Hexagon'],
            ['left' => '8 sides', 'right' => 'Octagon'],
            ['left' => '0 corners','right' => 'Circle'],
        ]), 2, 3);

        // Level 5 — Shape Properties
        $this->act($m, 5, 'Shape Properties!', 'quiz', $this->quiz([
            ['text' => 'Which shape can ROLL?',         'options' => ['Square','Triangle','Circle','Rectangle'], 'correct' => 'Circle'],
            ['text' => 'Which shape has the MOST sides?','options' => ['Triangle','Square','Pentagon','Hexagon'], 'correct' => 'Hexagon'],
            ['text' => 'A square has how many corners?','options' => ['2','3','4','5'], 'correct' => '4'],
            ['text' => 'A triangle has how many corners?','options' => ['2','3','4','5'],'correct' => '3'],
            ['text' => 'Which shape looks like a ring?','options' => ['Square','Circle','Oval','Diamond'],       'correct' => 'Circle'],
        ]), 1, 3);

        $this->act($m, 5, 'More Real Life Shapes!', 'matching', $this->matching([
            ['left' => '🍩 Donut',   'right' => 'Circle'],
            ['left' => '🧀 Cheese',  'right' => 'Triangle'],
            ['left' => '📦 Box',     'right' => 'Square'],
            ['left' => '🏈 Football','right' => 'Oval'],
        ]), 2, 3);

        // Level 6 — 3D Shapes
        $this->act($m, 6, 'Meet 3D Shapes!', 'quiz', $this->quiz([
            ['text' => 'A ball 🏀 is a…',       'options' => ['Cube','Sphere','Cone','Cylinder'], 'correct' => 'Sphere'],
            ['text' => 'A box 📦 is a…',         'options' => ['Cube','Sphere','Cone','Cylinder'], 'correct' => 'Cube'],
            ['text' => 'An ice cream 🍦 is a…',  'options' => ['Cube','Sphere','Cone','Cylinder'], 'correct' => 'Cone'],
            ['text' => 'A can of soup 🥫 is a…', 'options' => ['Cube','Sphere','Cone','Cylinder'], 'correct' => 'Cylinder'],
            ['text' => 'How many faces does a cube have?','options' => ['4','5','6','8'], 'correct' => '6'],
        ]), 1, 3);

        $this->act($m, 6, '3D Shape Match!', 'matching', $this->matching([
            ['left' => '🏀 Ball',       'right' => 'Sphere'],
            ['left' => '📦 Box',        'right' => 'Cube'],
            ['left' => '🍦 Ice Cream',  'right' => 'Cone'],
            ['left' => '🥫 Soup Can',   'right' => 'Cylinder'],
        ]), 2, 3);

        // Level 7 — Shape Patterns
        $this->act($m, 7, 'Shape Patterns!', 'quiz', $this->quiz([
            ['text' => '⬛🔺⬛🔺 — what comes next?',   'options' => ['⬛','🔺','⭕','▬'],  'correct' => '⬛'],
            ['text' => '⭕⭕⬛⭕⭕⬛ — what comes next?','options' => ['⬛','⭕','🔺','▬'],  'correct' => '⭕'],
            ['text' => 'How many sides does an oval have?', 'options' => ['0','1','2','3'],        'correct' => '0'],
            ['text' => 'Which shape has equal width and height?','options' => ['Rectangle','Square','Triangle','Oval'],'correct' => 'Square'],
            ['text' => 'A star ⭐ has how many points?',  'options' => ['4','5','6','7'],          'correct' => '5'],
        ]), 1, 3);

        $this->act($m, 7, 'Corners Match!', 'matching', $this->matching([
            ['left' => '3 corners', 'right' => 'Triangle'],
            ['left' => '4 corners', 'right' => 'Square'],
            ['left' => '0 corners', 'right' => 'Circle'],
            ['left' => '5 corners', 'right' => 'Pentagon'],
        ]), 2, 3);

        // Level 8 — Shapes Around Us
        $this->act($m, 8, 'Shapes Around Us!', 'quiz', $this->quiz([
            ['text' => 'A wheel is shaped like a…',     'options' => ['Square','Circle','Triangle','Rectangle'], 'correct' => 'Circle'],
            ['text' => 'A sandwich is shaped like a…',  'options' => ['Circle','Triangle','Square','Oval'],      'correct' => 'Triangle'],
            ['text' => 'A swimming pool is shaped like a…','options' => ['Circle','Triangle','Rectangle','Star'],'correct' => 'Rectangle'],
            ['text' => 'A button is shaped like a…',    'options' => ['Square','Triangle','Circle','Rectangle'], 'correct' => 'Circle'],
            ['text' => 'A kite is shaped like a…',      'options' => ['Square','Circle','Oval','Diamond'],       'correct' => 'Diamond'],
        ]), 1, 3);

        $this->act($m, 8, 'School Shapes Match!', 'matching', $this->matching([
            ['left' => '🖊 Pencil tip',   'right' => 'Triangle'],
            ['left' => '📐 Set Square',   'right' => 'Triangle'],
            ['left' => '🖥 Computer Screen','right' => 'Rectangle'],
            ['left' => '🔘 Button',       'right' => 'Circle'],
        ]), 2, 3);

        // Level 9 — Same & Different
        $this->act($m, 9, 'Same or Different?', 'quiz', $this->quiz([
            ['text' => 'Square and Rectangle — how are they alike?', 'options' => ['Both have 4 sides','Both are round','Both have 3 sides','Both are the same'],'correct' => 'Both have 4 sides'],
            ['text' => 'What is different about Circle from all shapes?','options' => ['It is red','It has no corners','It is big','It is 3D'],'correct' => 'It has no corners'],
            ['text' => 'Which two shapes both have 4 sides?',         'options' => ['Circle & Triangle','Square & Rectangle','Oval & Circle','Pentagon & Hexagon'],'correct' => 'Square & Rectangle'],
            ['text' => 'A cube is a 3D version of which flat shape?', 'options' => ['Triangle','Circle','Square','Rectangle'], 'correct' => 'Square'],
            ['text' => 'A sphere is a 3D version of which flat shape?','options' => ['Triangle','Circle','Square','Rectangle'],'correct' => 'Circle'],
        ]), 1, 3);

        $this->act($m, 9, 'Flat vs 3D Match!', 'matching', $this->matching([
            ['left' => 'Square (flat)',   'right' => 'Cube (3D)'],
            ['left' => 'Circle (flat)',   'right' => 'Sphere (3D)'],
            ['left' => 'Triangle (flat)', 'right' => 'Cone (3D)'],
            ['left' => 'Rectangle (flat)','right' => 'Cylinder (3D)'],
        ]), 2, 3);

        // Level 10 — Shape Master
        $this->act($m, 10, 'Shape Champion! 🏆', 'quiz', $this->quiz([
            ['text' => 'How many sides does a hexagon have?', 'options' => ['5','6','7','8'],                 'correct' => '6'],
            ['text' => 'A cone has how many flat faces?',     'options' => ['0','1','2','3'],                 'correct' => '1'],
            ['text' => 'Which shape has the fewest sides?',   'options' => ['Square','Pentagon','Triangle','Hexagon'],'correct' => 'Triangle'],
            ['text' => 'An octagon has how many sides?',      'options' => ['6','7','8','9'],                 'correct' => '8'],
            ['text' => 'Which is NOT a 3D shape?',           'options' => ['Cube','Sphere','Circle','Cylinder'],'correct' => 'Circle'],
            ['text' => 'A cylinder looks like a…',           'options' => ['Ball','Box','Can','Cone'],        'correct' => 'Can'],
        ]), 1, 3);

        $this->act($m, 10, 'Ultimate Shape Match!', 'matching', $this->matching([
            ['left' => '🎲 Dice',    'right' => 'Cube'],
            ['left' => '🌐 Globe',   'right' => 'Sphere'],
            ['left' => '🏔 Mountain','right' => 'Triangle'],
            ['left' => '🥚 Egg',     'right' => 'Oval'],
        ]), 2, 3);
    }

    // ================================================================== WORDS
    private function words(?Module $m): void
    {
        if (!$m) return;

        // Level 1 — Animal Words
        $this->act($m, 1, 'Animal Names!', 'quiz', $this->quiz([
            ['text' => '🐱', 'options' => ['Cat','Dog','Fish','Bird'],   'correct' => 'Cat'],
            ['text' => '🐶', 'options' => ['Cat','Dog','Fish','Bird'],   'correct' => 'Dog'],
            ['text' => '🐟', 'options' => ['Cat','Dog','Fish','Bird'],   'correct' => 'Fish'],
            ['text' => '🐦', 'options' => ['Cat','Dog','Fish','Bird'],   'correct' => 'Bird'],
            ['text' => '🐰', 'options' => ['Cat','Dog','Rabbit','Bird'], 'correct' => 'Rabbit'],
        ]), 1, 3);

        $this->act($m, 1, 'Spell CAT! 🐱', 'quiz',
            $this->wordBuilder('CAT', '🐱', ['B','X','M','S']), 2, 3);

        // Level 2 — More Animals
        $this->act($m, 2, 'Big Animals!', 'quiz', $this->quiz([
            ['text' => '🐘', 'options' => ['Elephant','Lion','Tiger','Monkey'],  'correct' => 'Elephant'],
            ['text' => '🦁', 'options' => ['Elephant','Lion','Tiger','Giraffe'], 'correct' => 'Lion'],
            ['text' => '🐒', 'options' => ['Elephant','Lion','Tiger','Monkey'],  'correct' => 'Monkey'],
            ['text' => '🐊', 'options' => ['Crocodile','Snake','Turtle','Frog'], 'correct' => 'Crocodile'],
            ['text' => '🦒', 'options' => ['Elephant','Lion','Tiger','Giraffe'], 'correct' => 'Giraffe'],
        ]), 1, 3);

        $this->act($m, 2, 'Spell DOG! 🐶', 'quiz',
            $this->wordBuilder('DOG', '🐶', ['C','A','T','X']), 2, 3);

        // Level 3 — Opposites
        $this->act($m, 3, "What's the Opposite?", 'quiz', $this->quiz([
            ['text' => 'Opposite of BIG is…',  'options' => ['Small','Tall','Long','Wide'],     'correct' => 'Small'],
            ['text' => 'Opposite of HOT is…',  'options' => ['Warm','Cool','Cold','Wet'],       'correct' => 'Cold'],
            ['text' => 'Opposite of DAY is…',  'options' => ['Morning','Evening','Night','Noon'],'correct' => 'Night'],
            ['text' => 'Opposite of UP is…',   'options' => ['Left','Right','Down','Side'],     'correct' => 'Down'],
            ['text' => 'Opposite of HAPPY is…','options' => ['Sad','Tired','Sleepy','Hungry'],  'correct' => 'Sad'],
        ]), 1, 3);

        $this->act($m, 3, 'Spell SUN! ☀️', 'quiz',
            $this->wordBuilder('SUN', '☀️', ['A','T','O','X']), 2, 3);

        // Level 4 — Body Parts
        $this->act($m, 4, 'Body Parts!', 'quiz', $this->quiz([
            ['text' => 'We use this to see 👀',   'options' => ['Eyes','Ears','Nose','Mouth'],  'correct' => 'Eyes'],
            ['text' => 'We use this to hear 👂',  'options' => ['Eyes','Ears','Nose','Mouth'],  'correct' => 'Ears'],
            ['text' => 'We use this to smell 👃', 'options' => ['Eyes','Ears','Nose','Mouth'],  'correct' => 'Nose'],
            ['text' => 'We use this to eat 😮',   'options' => ['Eyes','Ears','Nose','Mouth'],  'correct' => 'Mouth'],
            ['text' => 'We use these to walk 🦵', 'options' => ['Arms','Hands','Legs','Feet'],  'correct' => 'Legs'],
        ]), 1, 3);

        $this->act($m, 4, 'Spell HAT! 🎩', 'quiz',
            $this->wordBuilder('HAT', '🎩', ['B','X','M','S']), 2, 3);

        // Level 5 — Food Words
        $this->act($m, 5, 'Food Names!', 'quiz', $this->quiz([
            ['text' => '🍕', 'options' => ['Pizza','Pasta','Bread','Cake'],      'correct' => 'Pizza'],
            ['text' => '🍎', 'options' => ['Orange','Apple','Mango','Grape'],    'correct' => 'Apple'],
            ['text' => '🍌', 'options' => ['Mango','Papaya','Banana','Lemon'],   'correct' => 'Banana'],
            ['text' => '🥕', 'options' => ['Potato','Carrot','Onion','Garlic'],  'correct' => 'Carrot'],
            ['text' => '🍰', 'options' => ['Bread','Cookie','Cake','Donut'],     'correct' => 'Cake'],
        ]), 1, 3);

        $this->act($m, 5, 'Spell BEE! 🐝', 'quiz',
            $this->wordBuilder('BEE', '🐝', ['A','T','O','X']), 2, 3);

        // Level 6 — Color Words
        $this->act($m, 6, 'Color Words!', 'quiz', $this->quiz([
            ['text' => 'Which word means 🔴?',  'options' => ['Red','Blue','Green','Black'],   'correct' => 'Red'],
            ['text' => 'Which word means 🔵?',  'options' => ['Red','Blue','Green','Yellow'],  'correct' => 'Blue'],
            ['text' => 'Which word means 🟡?',  'options' => ['White','Red','Yellow','Orange'],'correct' => 'Yellow'],
            ['text' => 'Which word means 🟢?',  'options' => ['Red','Blue','Green','Pink'],    'correct' => 'Green'],
            ['text' => 'Which word means ⚫?',  'options' => ['Gray','White','Black','Brown'],  'correct' => 'Black'],
        ]), 1, 3);

        $this->act($m, 6, 'Spell RED! 🔴', 'quiz',
            $this->wordBuilder('RED', '🔴', ['A','B','T','X']), 2, 3);

        // Level 7 — Action Words
        $this->act($m, 7, 'Action Words!', 'quiz', $this->quiz([
            ['text' => '🏃 This action is called…',  'options' => ['Run','Jump','Sleep','Sit'],   'correct' => 'Run'],
            ['text' => '😴 This action is called…',  'options' => ['Run','Jump','Sleep','Sit'],   'correct' => 'Sleep'],
            ['text' => '🦘 This action is called…',  'options' => ['Run','Jump','Swim','Fly'],    'correct' => 'Jump'],
            ['text' => '🏊 This action is called…',  'options' => ['Run','Jump','Swim','Fly'],    'correct' => 'Swim'],
            ['text' => '✈️ This action is called…',  'options' => ['Run','Jump','Swim','Fly'],    'correct' => 'Fly'],
        ]), 1, 3);

        $this->act($m, 7, 'Spell RUN! 🏃', 'quiz',
            $this->wordBuilder('RUN', '🏃', ['A','B','T','X']), 2, 3);

        // Level 8 — Family Words
        $this->act($m, 8, 'Family Words!', 'quiz', $this->quiz([
            ['text' => 'The woman who takes care of you at home is your…', 'options' => ['Mom','Dad','Aunt','Sister'],   'correct' => 'Mom'],
            ['text' => 'The man who takes care of you at home is your…',   'options' => ['Mom','Dad','Uncle','Brother'], 'correct' => 'Dad'],
            ['text' => 'Your parents child who is a girl is your…',        'options' => ['Brother','Sister','Cousin','Aunt'],'correct' => 'Sister'],
            ['text' => 'Your parents child who is a boy is your…',         'options' => ['Brother','Sister','Cousin','Uncle'],'correct' => 'Brother'],
            ['text' => 'Your mom or dads mom is your…',                    'options' => ['Aunt','Mom','Grandma','Sister'], 'correct' => 'Grandma'],
        ]), 1, 3);

        $this->act($m, 8, 'Spell MOM! 👩', 'quiz',
            $this->wordBuilder('MOM', '👩', ['A','B','T','X']), 2, 3);

        // Level 9 — Number Words
        $this->act($m, 9, 'Number Words!', 'quiz', $this->quiz([
            ['text' => 'Which word means 1?',  'options' => ['One','Two','Three','Four'],  'correct' => 'One'],
            ['text' => 'Which word means 2?',  'options' => ['One','Two','Three','Four'],  'correct' => 'Two'],
            ['text' => 'Which word means 5?',  'options' => ['Three','Four','Five','Six'], 'correct' => 'Five'],
            ['text' => 'Which word means 10?', 'options' => ['Eight','Nine','Ten','Seven'],'correct' => 'Ten'],
            ['text' => 'Which word means 3?',  'options' => ['One','Two','Three','Four'],  'correct' => 'Three'],
        ]), 1, 3);

        $this->act($m, 9, 'Spell TEN! 🔢', 'quiz',
            $this->wordBuilder('TEN', '🔢', ['A','B','O','X']), 2, 3);

        // Level 10 — Word Champion
        $this->act($m, 10, 'Word Champion! 🏆', 'quiz', $this->quiz([
            ['text' => 'Opposite of FAST is…',  'options' => ['Quick','Slow','Stop','Still'],   'correct' => 'Slow'],
            ['text' => 'Opposite of OPEN is…',  'options' => ['Unlock','Wide','Closed','Near'], 'correct' => 'Closed'],
            ['text' => 'Opposite of CLEAN is…', 'options' => ['Wet','Dirty','Messy','Old'],     'correct' => 'Dirty'],
            ['text' => 'Opposite of TALL is…',  'options' => ['Short','Tiny','Small','Thin'],   'correct' => 'Short'],
            ['text' => '🏠 is called a…',       'options' => ['House','Hotel','Hut','Hall'],    'correct' => 'House'],
            ['text' => '🌳 is called a…',       'options' => ['Bush','Flower','Tree','Plant'],  'correct' => 'Tree'],
        ]), 1, 3);

        $this->act($m, 10, 'Spell BUS! 🚌', 'quiz',
            $this->wordBuilder('BUS', '🚌', ['A','T','O','X']), 2, 3);
    }
}
