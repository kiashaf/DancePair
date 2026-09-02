<?php

namespace App\Services;

class MessageContentFilter
{
    public function containsForbiddenContactInfo(string $message): bool
    {
        $message = trim($message);

        /*
        |--------------------------------------------------------------------------
        | PHONE NUMBERS
        |--------------------------------------------------------------------------
        |
        | Detects common formats such as:
        |
        | 5145551234
        | 514-555-1234
        | 514 555 1234
        | (514) 555-1234
        | +1 514 555 1234
        | 514.555.1234
        |
        */

        $phonePatterns = [

            '/(?:\+?\d[\s\-\.\(\)]*){7,15}/',

            '/\b\d{3}[\s\-\.\)]*\d{3}[\s\-\.]*\d{4}\b/',

        ];

        foreach ($phonePatterns as $pattern) {
            if (preg_match($pattern, $message)) {
                return true;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | EMAIL ADDRESSES
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i',
                $message
            )
        ) {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | CONTACT / SOCIAL MEDIA PHRASES
        |--------------------------------------------------------------------------
        */

        $contactPatterns = [

            '/\bwhats[\s\-]?app\b/i',
            '/\btelegram\b/i',
            '/\bsignal\b/i',

            '/\binstagram\b/i',
            '/\binsta\b/i',

            '/\bfacebook\b/i',
            '/\bmessenger\b/i',

            '/\bsnapchat\b/i',
            '/\bsnap\b/i',

            '/\btiktok\b/i',

            '/\bphone\s*number\b/i',
            '/\bmobile\s*number\b/i',
            '/\bcell\s*number\b/i',

            '/\bcall\s+me\b/i',
            '/\btext\s+me\b/i',
            '/\bmessage\s+me\s+at\b/i',

            '/\bcontact\s+me\s+at\b/i',

            /*
            |--------------------------------------------------------------------------
            | French
            |--------------------------------------------------------------------------
            */

            '/\bnum[eé]ro\s+de\s+t[eé]l[eé]phone\b/iu',
            '/\bnum[eé]ro\s+de\s+cellulaire\b/iu',

            '/\bappelle[\s\-]?moi\b/iu',
            '/\bappelez[\s\-]?moi\b/iu',

            '/\btexte[\s\-]?moi\b/iu',

            '/\bcontacte[\s\-]?moi\b/iu',
            '/\bcontactez[\s\-]?moi\b/iu',

        ];

        foreach ($contactPatterns as $pattern) {
            if (preg_match($pattern, $message)) {
                return true;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SOCIAL HANDLES
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | @johnsmith
        |
        */

        if (
            preg_match(
                '/(^|\s)@[a-zA-Z0-9._]{3,}/',
                $message
            )
        ) {
            return true;
        }


        return false;
    }
}