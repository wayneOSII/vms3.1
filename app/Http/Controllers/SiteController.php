<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\TextWidget;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function about(): View
    {
        $widget = TextWidget::query()
            ->where('key', '=', 'about-page')
            ->where('active', '=', 1)
            ->first();

        return view('about', compact('widget'));
    }
}
