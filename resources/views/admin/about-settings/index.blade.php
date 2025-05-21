@extends('admin.layouts.app')

@section('title', 'About Page Settings')

@section('content')
<div class="w-full">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800">About Page Settings</h3>
        </div>
        <div class="p-4">
            <!-- General Information Section -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h4 class="font-medium text-gray-800">General App Information</h4>
                    </div>
                    <div class="p-4">
                        @if(session('success'))
                            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.about-settings.general.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">App Name</label>
                                <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    id="app_name" name="app_name" value="{{ $settings->app_name }}" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="app_version" class="block text-sm font-medium text-gray-700 mb-1">Version</label>
                                <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    id="app_version" name="app_version" value="{{ $settings->app_version }}" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="app_tagline" class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                                <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    id="app_tagline" name="app_tagline" value="{{ $settings->app_tagline }}" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="app_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    id="app_description" name="app_description" rows="5" required>{{ $settings->app_description }}</textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo (Optional)</label>
                                <div class="mt-1 flex items-center">
                                    <label class="w-full flex flex-col items-center px-4 py-2 bg-white rounded-md shadow-sm border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                                        <span class="my-1">Choose file</span>
                                        <input type="file" class="sr-only" id="logo" name="logo">
                                    </label>
                                </div>
                                @if($settings->logo_path)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="App Logo" class="h-24 w-auto object-contain border rounded">
                                    </div>
                                @endif
                            </div>
                            
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Features Section -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h4 class="font-medium text-gray-800">Features</h4>
                    </div>
                    <div class="p-4">
                        @if(session('success'))
                            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.about-settings.features.update') }}" method="POST">
                            @csrf
                            
                            <div id="features-container">
                                @foreach($settings->getFeatures() as $index => $feature)
                                    <div class="bg-white border rounded-lg mb-4 feature-item">
                                        <div class="p-4">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                                        <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                                            name="features[{{ $index }}][title]" value="{{ $feature['title'] }}" required>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome class)</label>
                                                        <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                                            name="features[{{ $index }}][icon]" value="{{ $feature['icon'] }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                                <textarea class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                                    name="features[{{ $index }}][description]" rows="2" required>{{ $feature['description'] }}</textarea>
                                            </div>
                                            <button type="button" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 remove-feature">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="flex space-x-2 mt-4">
                                <button type="button" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50" id="add-feature">
                                    Add Feature
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Support Section -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h4 class="font-medium text-gray-800">Contact & Support Information</h4>
                    </div>
                    <div class="p-4">
                        @if(session('success'))
                            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.about-settings.support.update') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="support_email" class="block text-sm font-medium text-gray-700 mb-1">Support Email</label>
                                <input type="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    id="support_email" name="support_email" value="{{ $settings->support_email }}" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                        id="live_chat_available" name="live_chat_available" {{ $settings->live_chat_available ? 'checked' : '' }}>
                                    <span class="ml-2">Live Chat Available 24/7</span>
                                </label>
                            </div>
                            
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Legal Section -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h4 class="font-medium text-gray-800">Legal Information</h4>
                    </div>
                    <div class="p-4">
                        @if(session('success'))
                            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.about-settings.legal.update') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="terms_url" class="block text-sm font-medium text-gray-700 mb-1">Terms of Service URL</label>
                                <input type="url" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    id="terms_url" name="terms_url" value="{{ $settings->terms_url }}">
                                <p class="mt-1 text-xs text-gray-500">Leave empty to use default page</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="privacy_url" class="block text-sm font-medium text-gray-700 mb-1">Privacy Policy URL</label>
                                <input type="url" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    id="privacy_url" name="privacy_url" value="{{ $settings->privacy_url }}">
                                <p class="mt-1 text-xs text-gray-500">Leave empty to use default page</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="cookie_url" class="block text-sm font-medium text-gray-700 mb-1">Cookie Policy URL</label>
                                <input type="url" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    id="cookie_url" name="cookie_url" value="{{ $settings->cookie_url }}">
                                <p class="mt-1 text-xs text-gray-500">Leave empty to use default page</p>
                            </div>
                            
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add feature functionality
    let featureIndex = {{ count($settings->getFeatures()) }};
    
    document.getElementById('add-feature')?.addEventListener('click', function() {
        const featuresContainer = document.getElementById('features-container');
        if (!featuresContainer) return;
        
        const newFeature = document.createElement('div');
        newFeature.className = 'bg-white border rounded-lg mb-4 feature-item';
        newFeature.innerHTML = `
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                name="features[\${featureIndex}][title]" required>
                        </div>
                    </div>
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome class)</label>
                            <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                name="features[\${featureIndex}][icon]" value="fa-star" required>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                        name="features[\${featureIndex}][description]" rows="2" required></textarea>
                </div>
                <button type="button" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 remove-feature">
                    Remove
                </button>
            </div>
        `;
        
        featuresContainer.appendChild(newFeature);
        featureIndex++;
    });
    
    // Remove feature functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-feature')) {
            const featureItem = e.target.closest('.feature-item');
            if (featureItem) {
                featureItem.remove();
                
                // Re-index remaining features
                document.querySelectorAll('.feature-item').forEach((item, index) => {
                    item.querySelectorAll('input, textarea').forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            const newName = name.replace(/features\[\d+\]/, `features[${index}]`);
                            input.setAttribute('name', newName);
                        }
                    });
                });
                
                featureIndex = document.querySelectorAll('.feature-item').length;
            }
        }
    });
    
    // Update file input display
    const logoInput = document.getElementById('logo');
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const fileName = e.target.files[0].name;
                const span = e.target.parentElement.querySelector('span');
                if (span) {
                    span.textContent = fileName;
                }
            }
        });
    }
});
</script>
@endpush