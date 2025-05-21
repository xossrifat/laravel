<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $template->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        .title {
            font-size: 1.5rem;
            color: #333;
        }
        .back-link {
            color: #4f46e5;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .info-box {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            min-width: 120px;
        }
        .preview-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        .preview-header {
            background-color: #f9fafb;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .preview-subject {
            font-weight: bold;
            font-size: 1.1rem;
        }
        .preview-content {
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .variables {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }
        .variables-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .variable-item {
            margin-bottom: 5px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">Email Template Preview: {{ $template->name }}</h1>
            <a href="{{ route('admin.email_templates.edit', $template->id) }}" class="back-link">&larr; Back to Edit</a>
        </div>
        
        <div class="info-box">
            <div class="info-item">
                <span class="label">Template Name:</span> {{ $template->name }}
            </div>
            <div class="info-item">
                <span class="label">Slug:</span> {{ $template->slug }}
            </div>
            <div class="info-item">
                <span class="label">Type:</span> {{ ucfirst($template->type) }}
            </div>
            <div class="info-item">
                <span class="label">Status:</span> 
                <span style="color: {{ $template->active ? 'green' : 'red' }}">
                    {{ $template->active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
        
        <div class="preview-container">
            <div class="preview-header">
                <div class="preview-subject">Subject: {{ $subject }}</div>
            </div>
            
            <div class="preview-content">
                {!! $content !!}
            </div>
        </div>
        
        @if($template->variables)
        <div class="variables">
            <div class="variables-title">Available Variables:</div>
            @php
                $variables = $template->variables;
                if (is_string($variables)) {
                    $variables = json_decode($variables, true) ?: [];
                }
            @endphp
            
            @foreach($variables as $key => $desc)
                <div class="variable-item">
                    <code>{{$key}}</code>: {{ $desc }}
                </div>
            @endforeach
        </div>
        @endif
    </div>
</body>
</html> 