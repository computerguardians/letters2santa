<?php

// Lower-case words/phrases that send a letter to the manual moderation queue when found in the body or child name.
// Keep this list conservative: a false positive only means an admin looks at the letter.
return [
    'words' => [
        'fuck', 'fucking', 'shit', 'bitch', 'cunt', 'asshole', 'arsehole', 'bastard', 'dick', 'pussy', 'wanker',
        'slut', 'whore', 'nigger', 'nigga', 'faggot', 'retard', 'kill yourself', 'kys', 'porn', 'sex',
    ],
    // Words that hint at identifying information we never publish.
    'pii_hints' => [
        'school', 'primary school', 'grade', 'class', 'teacher', 'address', 'phone', 'mobile', 'street', 'surname', 'last name',
    ],
];
