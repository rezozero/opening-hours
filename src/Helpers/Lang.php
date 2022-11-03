<?php
namespace RZ\OpeningHours\Helpers;

class Lang
{
    /**
     * @param string $string
     * @param array $args
     * @param string $locale
     * @return string
     */
    public static function t(string $string, array $args = [], string $locale = "fr"): string
    {
        global $translation;
        $message = null;
        if (file_exists(__DIR__.'/../lang/'.$locale.'/message.php')) {
            $message = include(__DIR__.'/../lang/'.$locale.'/message.php');
        }
        $translation[$locale] = $message;

        // Search for a translated string.
        if (isset($translation[$locale][$string])) {
            $string = $translation[$locale][$string];
        }

        // Replace arguments if present.
        if (empty($args)) {
            return $string;
        } else {
            /**
             * @var string $key
             * @var mixed $value
             */
            foreach ($args as $key => $value) {
                switch ($key[0]) {
                    case '!':
                    case '@':
                    case '%':
                    default:
                        $args[$key] = $value;
                        break;
                }
            }

            return strtr($string, $args);
        }
    }
}
