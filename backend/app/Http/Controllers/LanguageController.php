<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Change the application language.
     *
     * @param string $lang Language code (en, ar)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(string $lang)
    {
        // Validate language code
        $allowedLanguages = ['en', 'ar'];
        if (!in_array($lang, $allowedLanguages)) {
            $lang = 'en'; // Default to English if invalid
        }

        // Set locale in session
        session()->put('locale', $lang);
        
        // Set locale in application
        App::setLocale($lang);
        
        // Redirect back to previous page
        return redirect()->back();
    }
}
