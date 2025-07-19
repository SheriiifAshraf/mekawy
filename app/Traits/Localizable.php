<?php

namespace App\Traits;

trait Localizable
{
    public function localize()
    {
        $locales = [];
        foreach ($this->locales() as $locale) {
            foreach ($this->getTranslatableAttributes() as $attribute) {
                $locales[$locale][$attribute] = $this->getTranslation($attribute, $locale);
            }
        }
        return $locales;
    }
}
