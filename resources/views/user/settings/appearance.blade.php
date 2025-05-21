@extends('layouts.app')

@section('title', 'Appearance Settings')

@section('content')
<div class="mobile-header">
    @include('user.settings.settings')
    @include('layouts.banner')
</div>
<div class="container">
@include('user.settings.settings')
    <main class="content mobile-content">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="section">
            <div class="section-header">
                <span>Theme Settings</span>
            </div>
            <p>Choose your preferred theme for the application</p>
            
            <form action="{{ route('user.theme.update') }}" method="POST" id="theme-form">
                @csrf
                <div class="theme-options">
                    <div class="theme-option {{ auth()->user()->theme == 'light' ? 'active' : '' }}" onclick="selectTheme('light')">
                        <div class="theme-preview light-preview">
                            <div class="preview-header"></div>
                            <div class="preview-content">
                                <div class="preview-box"></div>
                                <div class="preview-box"></div>
                            </div>
                        </div>
                        <div class="theme-label">
                            <input type="radio" name="theme" value="light" {{ auth()->user()->theme == 'light' ? 'checked' : '' }} id="light-theme">
                            <label for="light-theme">Light Theme</label>
                        </div>
                    </div>
                    
                    <div class="theme-option {{ auth()->user()->theme == 'dark' ? 'active' : '' }}" onclick="selectTheme('dark')">
                        <div class="theme-preview dark-preview">
                            <div class="preview-header"></div>
                            <div class="preview-content">
                                <div class="preview-box"></div>
                                <div class="preview-box"></div>
                            </div>
                        </div>
                        <div class="theme-label">
                            <input type="radio" name="theme" value="dark" {{ auth()->user()->theme == 'dark' ? 'checked' : '' }} id="dark-theme">
                            <label for="dark-theme">Dark Theme</label>
                        </div>
                    </div>
                    
                    <div class="theme-option {{ auth()->user()->theme == 'system' ? 'active' : '' }}" onclick="selectTheme('system')">
                        <div class="theme-preview system-preview">
                            <div class="preview-half light-preview">
                                <div class="preview-header"></div>
                                <div class="preview-dot"></div>
                            </div>
                            <div class="preview-half dark-preview">
                                <div class="preview-header"></div>
                                <div class="preview-dot"></div>
                            </div>
                        </div>
                        <div class="theme-label">
                            <input type="radio" name="theme" value="system" {{ auth()->user()->theme == 'system' ? 'checked' : '' }} id="system-theme">
                            <label for="system-theme">System Default</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-update">Save Theme</button>
                </div>
            </form>
        </div>
        
        <div class="section">
            <div class="section-header">
                <span>Font Size</span>
            </div>
            <p>Adjust the text size for better readability</p>
            
            <div class="font-size-slider">
                <span class="font-size-label small">A</span>
                <input type="range" min="1" max="5" value="3" class="slider" id="font-size-slider">
                <span class="font-size-label large">A</span>
            </div>
            <div class="form-actions">
                <button class="btn-update disabled">Save Font Size</button>
                <p class="coming-soon">Coming soon</p>
            </div>
        </div>
        @include('layouts.banner')
    </main>
</div>

<style>
.container {
  display: flex;
        min-height: 100vh;
        max-width: 100%;
  margin: 0;
  padding: 0;
}

.content {
  flex: 1;
  padding: 30px;
}


html.dark .section {
  background-color:rgb(27, 27, 27) !important;
}

.section {
  background-color: #fff;
  margin-bottom: 20px;
  padding: 20px;
  border-radius: 10px;
  border: 1px solid #e3e3e3;
}

.section-header {
        display: flex;
  justify-content: space-between;
  font-weight: bold;
  margin-bottom: 10px;
    }
    
    .theme-options {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 20px;
    }
    
    .theme-option {
  width: calc(33.33% - 14px);
  border: 2px solid #eee;
  border-radius: 10px;
  padding: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.theme-option:hover {
  border-color: #f8b500;
}

.theme-option.active {
  border-color: #f8b500;
  background-color: #fffbeb;
    }
    
    .theme-preview {
  height: 120px;
  border-radius: 6px;
        overflow: hidden;
  margin-bottom: 10px;
}

.light-preview {
  background-color: #f8f9fa;
  color: #333;
}

.dark-preview {
  background-color: #333;
  color: #f8f9fa;
}

.system-preview {
  display: flex;
  overflow: hidden;
}

.preview-half {
  width: 50%;
  height: 100%;
}

.preview-header {
  height: 20px;
  background-color: currentColor;
  opacity: 0.2;
}

.preview-content {
  padding: 10px;
        display: flex;
        flex-direction: column;
  gap: 10px;
}

.preview-box {
  height: 30px;
  background-color: currentColor;
  opacity: 0.1;
  border-radius: 4px;
}

.preview-dot {
  width: 20px;
  height: 20px;
  background-color: currentColor;
  opacity: 0.3;
  border-radius: 50%;
  margin: 20px auto;
}

.theme-label {
        text-align: center;
  font-size: 14px;
}

.theme-label input[type="radio"] {
  margin-right: 5px;
}

.form-actions {
  margin-top: 20px;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

.coming-soon {
  margin-left: 10px;
  font-size: 12px;
  color: #999;
  font-style: italic;
}

.btn-update {
  background-color: #f8b500;
  color: white;
        border: none;
  padding: 8px 15px;
  border-radius: 5px;
        cursor: pointer;
}

.btn-update.disabled {
  background-color: #ddd;
  cursor: not-allowed;
}

.font-size-slider {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-top: 20px;
}

.font-size-label {
  font-weight: bold;
}

.font-size-label.small {
  font-size: 12px;
}

.font-size-label.large {
  font-size: 24px;
}

.slider {
  flex-grow: 1;
  height: 8px;
  border-radius: 4px;
  background: #eee;
  outline: none;
  -webkit-appearance: none;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #f8b500;
  cursor: pointer;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 20px;
}

.alert-error {
  background-color: #f8d7da;
  color: #721c24;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 20px;
}

.mobile-header {
    display: none;
}

@media (max-width: 768px) {
        .mobile-header {
            display: block;
            margin-bottom: 10px;
        }
        
        .sidebar {
            display: none;
        }
        
        .sidebar.active {
            display: block;
        }
        
        .container {
            padding-top: 0;
        }
        
        .theme-options {
    flex-direction: column;
        }
        
  .theme-option {
    width: 100%;
        }
    }
</style>

<script>
function selectTheme(theme) {
    document.querySelectorAll('.theme-option').forEach(option => {
        option.classList.remove('active');
    });
    
    document.querySelector(`input[value="${theme}"]`).checked = true;
    document.querySelector(`input[value="${theme}"]`).closest('.theme-option').classList.add('active');
}
</script>
@endsection     