<?php

namespace SkyRaptor\LaravelSalutations;

use Illuminate\Support\Facades\App;
use SkyRaptor\LaravelSalutations\Exceptions\InvalidIndexException;
use SkyRaptor\LaravelSalutations\Exceptions\InvalidKeyException;

/**
 * SkyRaptor\LaravelSalutations\Salutation.
 */
class Salutations
{
    /**
     * The current application locale.
     *
     * @property string
     */
    private $locale;

    /**
     * Constructor to retrieve the application
     * locale and cache it for simpler useage.
     */
    public function __construct()
    {
        $this->locale = App::getLocale();
    }

    /**
     * Changes the locale to be used.
     *
     * @param string $locale The locale to be set
     *
     * @return void
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Get the requested salutation based on
     * its key in the translations lookup.
     *
     * Returns null if the requested index does not exist.
     *
     * @param int $index The salutation index in the lookup table.
     *
     * @return string
     */
    public function index($index)
    {
        if (!array_key_exists($index, $this->lookup)) {
            throw new InvalidIndexException('There is no salutation for the given index. Provided index: '.$index);
        }
        return $this->key($this->lookup[$index]);
    }

    /**
     * Get the requested salutation based on
     * its value in the translations lookup.
     *
     * @param string $key The salutation key.
     *
     * @return string
     */
    public function key($key)
    {
        if (!in_array($key, $this->lookup)) {
            throw new InvalidKeyException('There is no salutation for the given key. Provided key: '.$key);
        }
        return self::trans_fb('salutations::salutations.'.$key, $this->locale);
    }

    /**
     * Translate the given message with a fallback string if none exists.
     *
     * @param string $id
     * @param string $fallback
     * @param array  $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return \Symfony\Component\Translation\TranslatorInterface|string
     */
    private static function trans_fb($id, $locale)
    {
        return $id === ($translation = trans($id, [], $locale)) ? trans($id, [], config('salutations.fallback_locale')) : $translation; // Default fallback
    }

    /**
     * Shorthand for the male salutation.
     *
     * @return string
     */
    public function male()
    {
        return $this->key('male');
    }

    /**
     * Shorthand for the female salutation.
     *
     * @return string
     */
    public function female()
    {
        return $this->key('female');
    }

    /**
     * Shorthand for the other salutation.
     *
     * @return string
     */
    public function other()
    {
        return $this->key('other');
    }

    /**
     * Easy access to the lookup.
     *
     * @param array $include Array containing indexes or keys of salutations to include
     *
     * @return array
     */
    public function lookup()
    {
        return $this->lookup;
    }

    /**
     * Easy access to the lookup, allwing to only include specific entries.
     * These can be specified by index or key.
     *
     * @param array $include Array containing indexes or keys of salutations to include
     *
     * @return array
     */
    public function lookupInclude($includes = [])
    {
        return $this->loopLookup($includes, [], true);
    }

    /**
     * Easy access to the lookup, allowing to exclude specific entries.
     * These can be specified by index or key.
     *
     * @param int|string $exclude Array containing indexes or keys of salutations to include
     *
     * @return array
     */
    public function lookupExclude($excludes = [])
    {
        return $this->loopLookup($excludes, $this->lookup);
    }

    /**
     * Loops the given filters and either adds
     * or removes the given lookup entry from the output.
     *
     * @param int|string $exclude Array containing indexes or keys of salutations to include
     *
     * @return array
     */
    private function loopLookup($filters = [], $output = [], $mode = false)
    {
        foreach ($filters as $filter) {
            if (is_string($filter)) {
                $index = array_search($filter, $this->lookup);
                if ($index === false) {
                    continue;
                }
            } else {
                $index = $filter;
                if (!array_key_exists($index, $this->lookup)) {
                    continue;
                }
            }

            if ($mode) {
                array_push($output, $this->lookup[$index]);
            } else {
                unset($output[$index]);
            }
        }

        return $output;
    }

    /**
     * Lookup for translation keys.
     *
     * @property array
     */
    private $lookup = [
        0 => 'male',   //0
        1 => 'female', //1

        2 => 'other',   //2 - To be used if it is not on the list ¯\_(ツ)_/¯

        //...
    ];
}
