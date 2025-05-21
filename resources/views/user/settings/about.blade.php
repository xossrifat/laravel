@extends('layouts.app')

@section('title', 'About')

@section('content')
<div class="mobile-header">
    @include('user.settings.settings')
    @include('layouts.banner')
</div>
<div class="container">
  @include('user.settings.settings')  
    <main class="content mobile-content">
        <div class="section">
            <div class="section-header">
                <span>About {{ $aboutSettings->app_name }}</span>
            </div>
            <div class="app-info">
                <div class="app-logo">
                    @if($aboutSettings->logo_path)
                        <img src="{{ asset('storage/' . $aboutSettings->logo_path) }}" alt="{{ $aboutSettings->app_name }} Logo" class="w-full h-full">
                    @else
                        <img src="{{ asset('images/logo.png') }}" alt="{{ $aboutSettings->app_name }} Logo" class="w-full h-full">
                    @endif
                </div>
                <div class="app-details">
                    <h1>{{ $aboutSettings->app_name }}</h1>
                    <p class="version">Version {{ $aboutSettings->app_version }}</p>
                    <p class="tagline">{{ $aboutSettings->app_tagline }}</p>
                </div>
            </div>
            
            <div class="app-description">
                <p>{{ $aboutSettings->app_description }}</p>
            </div>
        </div>
        
        <div class="section">
            <div class="section-header">
                <span>Features</span>
            </div>
            <div class="features-list">
                @foreach($aboutSettings->getFeatures() as $feature)
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas {{ $feature['icon'] }}"></i>
                    </div>
                    <div class="feature-content">
                        <h3>{{ $feature['title'] }}</h3>
                        <p>{{ $feature['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="section">
            <div class="section-header">
                <span>Contact & Support</span>
            </div>
            <div class="contact-info">
                <p>If you have any questions, feedback, or need assistance, please contact our support team:</p>
                
                <div class="contact-methods">
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-content">
                            <h3>Email</h3>
                            <p><a href="mailto:{{ $aboutSettings->support_email }}">{{ $aboutSettings->support_email }}</a></p>
                        </div>
                    </div>
                    
                    @if($aboutSettings->live_chat_available)
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="contact-content">
                            <h3>Live Chat</h3>
                            <p>Available 24/7 through the app</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="section">
            <div class="section-header">
                <span>Legal Information</span>
            </div>
            <div class="legal-links">
                <a href="{{ $aboutSettings->terms_url }}" class="legal-link">Terms of Service</a>
                <a href="{{ $aboutSettings->privacy_url }}" class="legal-link">Privacy Policy</a>
                <a href="{{ $aboutSettings->cookie_url }}" class="legal-link">Cookie Policy</a>
            </div>
        </div>
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
  margin-bottom: 15px;
}

.app-info {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.app-logo {
  width: 80px;
  height: 80px;
  border-radius: 16px;
  overflow: hidden;
  margin-right: 20px;
  background-color: #f7c700;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.app-logo img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.app-details h1 {
  font-size: 24px;
  margin: 0 0 5px 0;
  color: #333;
}

.version {
  font-size: 14px;
  color: #888;
  margin: 0 0 5px 0;
}
html.dark .section {
  background-color:rgb(27, 27, 27) !important;
}
.tagline {
  font-size: 16px;
  margin: 0;
  color: #666;
}

.app-description {
  font-size: 15px;
  line-height: 1.5;
  color: #555;
}

.features-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 15px;
}

.feature {
  display: flex;
  align-items: flex-start;
  padding: 15px;
  border: 1px solid #eee;
  border-radius: 10px;
}

.feature-icon {
  width: 40px;
  height: 40px;
  background-color: #fffbeb;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #f8b500;
  font-size: 18px;
  margin-right: 15px;
}

.feature-content h3 {
  font-size: 16px;
  margin: 0 0 5px 0;
}

.feature-content p {
  font-size: 14px;
  margin: 0;
  color: #666;
}

.contact-methods {
  margin-top: 20px;
}

.contact-method {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}

.contact-icon {
  width: 40px;
  height: 40px;
  background-color: #fffbeb;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #f8b500;
  font-size: 18px;
  margin-right: 15px;
}

.contact-content h3 {
  font-size: 16px;
  margin: 0 0 5px 0;
}

.contact-content p {
  font-size: 14px;
  margin: 0;
  color: #666;
}

.contact-content a {
  color: #007bff;
  text-decoration: none;
}

.contact-content a:hover {
  text-decoration: underline;
}

.legal-links {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
}

.legal-link {
  color: #007bff;
  text-decoration: none;
  font-size: 14px;
}

.legal-link:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .app-info {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .app-logo {
    margin-bottom: 15px;
  }
  
  .features-list {
    grid-template-columns: 1fr;
  }
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
}
</style>
@endsection