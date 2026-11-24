<?php

namespace App\Controllers;

class PreferenceController extends BaseController
{
    public function setLang(string $lang)
    {
        if (in_array($lang, ['id', 'en'], true)) {
            session()->set('se_lang', $lang);
        }

        $referer = $this->request->getHeaderLine('Referer');
        if ($referer !== '' && str_starts_with($referer, base_url())) {
            return redirect()->to($referer);
        }

        return redirect()->to('/');
    }
}

