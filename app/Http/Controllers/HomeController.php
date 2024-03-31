<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $tones = [
            "Formal" => "👔",
            "Friendly" => "🙂",
            "Casual" => "😎",
            "Professional" => "💼",
            "Diplomatic" => "🤝",
            "Confident" => "💪",
            "Middle school" => "📕",
            "High school" => "📗",
            "Academic" => "🎓",
            "Simplified" => "📖",
            "Bold" => "🦄",
            "Empathetic" => "🤗",
            "Luxury" => "💎",
            "Engaging" => "👍",
            "Direct" => "➡️",
            "Persuasive" => "🎯",
        ];

        $outline = $request->session()->get('outline');

        if ($outline) {
            return view('welcome', [
                'outline' => $outline,
                'outline_id' => $outline->id,
                'tones' => $tones,
                'selected_tone' => $outline->tone,
            ]);
        }
    
        return view('welcome', [
            'tones' => $tones,
            'outline' => null,
            'outline_id' => null,
            'selected_tone' => 'Casual',
        ]);
    }
}
