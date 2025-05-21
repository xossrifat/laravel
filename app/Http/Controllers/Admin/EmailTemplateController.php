<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the email templates.
     */
    public function index()
    {
        $templates = EmailTemplate::orderBy('name')->paginate(10);
        return view('admin.email_templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new email template.
     */
    public function create()
    {
        return view('admin.email_templates.create');
    }

    /**
     * Store a newly created email template in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,reward,notification,system',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.email_templates.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Generate a slug from the name
        $slug = Str::slug($request->name);
        
        // Check if slug already exists, append a number if needed
        $count = 0;
        $originalSlug = $slug;
        while (EmailTemplate::where('slug', $slug)->exists()) {
            $count++;
            $slug = $originalSlug . '-' . $count;
        }

        $variables = $request->has('variables') ? json_decode($request->variables, true) : [];

        $template = new EmailTemplate();
        $template->name = $request->name;
        $template->slug = $slug;
        $template->subject = $request->subject;
        $template->content = $request->content;
        $template->variables = $variables;
        $template->type = $request->type;
        $template->active = $request->has('active');
        $template->save();

        return redirect()->route('admin.email_templates.index')
            ->with('success', 'Email template created successfully.');
    }

    /**
     * Show the form for editing the specified email template.
     */
    public function edit($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return view('admin.email_templates.edit', compact('template'));
    }

    /**
     * Update the specified email template in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,reward,notification,system',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.email_templates.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $template = EmailTemplate::findOrFail($id);
        
        // If name has changed, update the slug
        if ($template->name !== $request->name) {
            $slug = Str::slug($request->name);
            
            // Check if slug already exists, append a number if needed
            $count = 0;
            $originalSlug = $slug;
            while (EmailTemplate::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $count++;
                $slug = $originalSlug . '-' . $count;
            }
            
            $template->slug = $slug;
        }

        $variables = $request->has('variables') ? json_decode($request->variables, true) : [];

        $template->name = $request->name;
        $template->subject = $request->subject;
        $template->content = $request->content;
        $template->variables = $variables;
        $template->type = $request->type;
        $template->active = $request->has('active');
        $template->save();

        return redirect()->route('admin.email_templates.index')
            ->with('success', 'Email template updated successfully.');
    }

    /**
     * Remove the specified email template from storage.
     */
    public function destroy($id)
    {
        $template = EmailTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.email_templates.index')
            ->with('success', 'Email template deleted successfully.');
    }
    
    /**
     * Preview the email template.
     */
    public function preview($id)
    {
        $template = EmailTemplate::findOrFail($id);
        
        // Get example variables
        $exampleVars = [];
        if ($template->variables) {
            // Make sure variables is an array - handle both string and already-cast array scenarios
            $vars = $template->variables;
            if (is_string($vars)) {
                $vars = json_decode($vars, true) ?: [];
            }
            
            // Now iterate through the variables
            foreach ($vars as $varName => $varDesc) {
                $exampleVars[$varName] = "Example " . ucfirst($varName);
            }
        }
        
        // Add common variables
        $exampleVars = array_merge($exampleVars, [
            'userName' => 'John Doe',
            'userEmail' => 'example@example.com',
            'siteName' => ' RewardBazar',
            'siteUrl' => url('/'),
        ]);
        
        $content = $template->parseContent($exampleVars);
        $subject = $template->parseSubject($exampleVars);
        
        return view('admin.email_templates.preview', compact('template', 'content', 'subject'));
    }
}
