<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailTemplate extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'subject',
        'content',
        'variables',
        'type',
        'active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'variables' => 'array',
        'active' => 'boolean',
    ];
    
    /**
     * Parse template content with provided variables
     *
     * @param array $vars
     * @return string
     */
    public function parseContent(array $vars = []): string
    {
        $content = $this->content;
        
        foreach ($vars as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        return $content;
    }
    
    /**
     * Parse template subject with provided variables
     *
     * @param array $vars
     * @return string
     */
    public function parseSubject(array $vars = []): string
    {
        $subject = $this->subject;
        
        foreach ($vars as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
        }
        
        return $subject;
    }
}
