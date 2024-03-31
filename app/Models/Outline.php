<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outline extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'topic', 'tone', 'content'];

    public function getPromptMessages()
    {
        $message = [
            "Generate blog post outline.",
            "Title: {$this->title}",
            "Tone: {$this->tone}",
            "Important points to include:",
            "{$this->topic}",
        ];

        return [
            [
                'role' => 'user',
                'content' => implode("\n", $message)
            ]
        ];
    }
}
