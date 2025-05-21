@extends('layouts.admin')

@section('title', 'Telegram Messaging')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Telegram Messaging</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Telegram Messaging</li>
        </ol>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="row">
            <!-- Telegram Users Stats Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">{{ number_format($telegramUsers) }}</h4>
                                <div class="small">Telegram Users</div>
                            </div>
                            <div class="h1">
                                <i class="fab fa-telegram-plane"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <div class="small text-white">{{ $telegramPercentage }}% of all users</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Text Message Tab -->
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-comment me-1"></i>
                        Send Text Message
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.telegram.send-message') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Message Content <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                    id="message" name="message" rows="6" 
                                    placeholder="Enter your message here (HTML formatting supported)">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <strong>Formatting Options:</strong> 
                                    <code>&lt;b&gt;bold&lt;/b&gt;</code>, 
                                    <code>&lt;i&gt;italic&lt;/i&gt;</code>, 
                                    <code>&lt;u&gt;underline&lt;/u&gt;</code>, 
                                    <code>&lt;a href="URL"&gt;link&lt;/a&gt;</code>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Parse Mode</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="parse_mode" id="parseHTML" value="HTML" checked>
                                    <label class="form-check-label" for="parseHTML">
                                        HTML
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="parse_mode" id="parseMarkdown" value="Markdown">
                                    <label class="form-check-label" for="parseMarkdown">
                                        Markdown
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i> Send to All Telegram Users
                                </button>
                                <small class="form-text text-muted text-center mt-1">
                                    This will send a message to all {{ number_format($telegramUsers) }} Telegram users
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Photo Message Tab -->
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-image me-1"></i>
                        Send Photo Message
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.telegram.send-photo') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo <span class="text-danger">*</span></label>
                                <input class="form-control @error('photo') is-invalid @enderror" 
                                    type="file" id="photo" name="photo" accept="image/*">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maximum size: 10MB</div>
                            </div>
                            
                            <!-- Image preview -->
                            <div class="mb-3 d-none" id="imagePreviewContainer">
                                <label class="form-label">Preview:</label>
                                <div class="border rounded p-2 text-center">
                                    <img id="imagePreview" class="img-fluid img-thumbnail" style="max-height: 200px;" src="#" alt="Preview">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="caption" class="form-label">Caption (Optional)</label>
                                <textarea class="form-control @error('caption') is-invalid @enderror" 
                                    id="caption" name="caption" rows="3" 
                                    placeholder="Enter an optional caption for the photo">{{ old('caption') }}</textarea>
                                @error('caption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Max 1024 characters</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Parse Mode</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="parse_mode" id="parseHTMLPhoto" value="HTML" checked>
                                    <label class="form-check-label" for="parseHTMLPhoto">
                                        HTML
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="parse_mode" id="parseMarkdownPhoto" value="Markdown">
                                    <label class="form-check-label" for="parseMarkdownPhoto">
                                        Markdown
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-image me-2"></i> Send Photo to All Telegram Users
                                </button>
                                <small class="form-text text-muted text-center mt-1">
                                    This will send the photo to all {{ number_format($telegramUsers) }} Telegram users
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Message Card -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-vial me-1"></i>
                        Test Telegram Message
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.telegram.send-test') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="test_telegram_id" class="form-label">Telegram User ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('test_telegram_id') is-invalid @enderror" 
                                            id="test_telegram_id" name="test_telegram_id" 
                                            placeholder="e.g. 12345678" value="{{ old('test_telegram_id') }}">
                                        @error('test_telegram_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="test_message" class="form-label">Test Message <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('test_message') is-invalid @enderror" 
                                            id="test_message" name="test_message" 
                                            placeholder="Enter a test message" value="{{ old('test_message') ?? 'This is a test message from Admin Panel.' }}">
                                        @error('test_message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-info text-white">
                                    <i class="fas fa-paper-plane me-2"></i> Send Test Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Formatting Help Card -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <i class="fas fa-question-circle me-1"></i>
                        Formatting Help
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>HTML Formatting</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tag</th>
                                                <th>Example</th>
                                                <th>Result</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>&lt;b&gt;&lt;/b&gt;</code></td>
                                                <td><code>&lt;b&gt;Bold text&lt;/b&gt;</code></td>
                                                <td><strong>Bold text</strong></td>
                                            </tr>
                                            <tr>
                                                <td><code>&lt;i&gt;&lt;/i&gt;</code></td>
                                                <td><code>&lt;i&gt;Italic text&lt;/i&gt;</code></td>
                                                <td><em>Italic text</em></td>
                                            </tr>
                                            <tr>
                                                <td><code>&lt;u&gt;&lt;/u&gt;</code></td>
                                                <td><code>&lt;u&gt;Underline&lt;/u&gt;</code></td>
                                                <td><u>Underline</u></td>
                                            </tr>
                                            <tr>
                                                <td><code>&lt;a href=""&gt;&lt;/a&gt;</code></td>
                                                <td><code>&lt;a href="https://t.me/example"&gt;Link&lt;/a&gt;</code></td>
                                                <td><a href="#">Link</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Markdown Formatting</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Syntax</th>
                                                <th>Example</th>
                                                <th>Result</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>**text**</code></td>
                                                <td><code>**Bold text**</code></td>
                                                <td><strong>Bold text</strong></td>
                                            </tr>
                                            <tr>
                                                <td><code>_text_</code></td>
                                                <td><code>_Italic text_</code></td>
                                                <td><em>Italic text</em></td>
                                            </tr>
                                            <tr>
                                                <td><code>[text](url)</code></td>
                                                <td><code>[Link](https://t.me/example)</code></td>
                                                <td><a href="#">Link</a></td>
                                            </tr>
                                            <tr>
                                                <td><code>`text`</code></td>
                                                <td><code>`Monospace`</code></td>
                                                <td><code>Monospace</code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Image preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photo');
        const imagePreview = document.getElementById('imagePreview');
        const previewContainer = document.getElementById('imagePreviewContainer');
        
        if (photoInput) {
            photoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        previewContainer.classList.remove('d-none');
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                } else {
                    previewContainer.classList.add('d-none');
                }
            });
        }
    });
</script>
@endsection 