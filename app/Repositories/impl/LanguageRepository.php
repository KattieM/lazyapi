<?php

namespace App\Repositories;

use App\Language;

/**
 * Class LanguageRepository.
 */
class LanguageRepository implements LanguageRepositoryInterface
{
    public function model()
    {
        return Language::class;
    }

    public function addLanguage($name)
    {
        $language = Language::where('name', $name)->get();
        return $language->first()->id;
    }
}
