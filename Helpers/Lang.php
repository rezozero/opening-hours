<?php
namespace Lib\OpeningHours\Helpers;

class Lang {


    public static function t($string, $args = array(), $locale = "fr")
    {
        global $translation;
        if (file_exists(__DIR__.'/../lang/'.$locale.'/message.php')) {
            $message = include(__DIR__.'/../lang/'.$locale.'/message.php');
        }
        $translation[$locale] = $message;

        // Search for a translated string.
        if ( isset($translation[$locale][$string]) ) {
            $string = $translation[$locale][$string];
        }
        // Replace arguments if present.
        if ( empty($args) ) {
            return $string;
        } else {
            foreach ( $args as $key => $value ) {
                switch ( $key[0] ) {
                    case '!':
                    case '@':
                    case '%':
                    default: $args[$key] = $value; break;
                }
            }

            return strtr($string, $args);
        }
    }
}
