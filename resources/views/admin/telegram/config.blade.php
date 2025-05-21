@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Telegram Configuration</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Telegram Configuration</li>
    </ol>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-cogs me-1"></i>
            Bot Configuration
        </div>
        <div class="card-body">
            <form action="{{ route('admin.telegram.config.update') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="bot_token" class="form-label">Bot Token</label>
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control" 
                            id="bot_token" 
                            name="bot_token" 
                            value="{{ $botToken }}" 
                            placeholder="Enter your Telegram bot token"
                        >
                        <button type="button" class="btn btn-outline-primary" id="test-connection">Test Connection</button>
                    </div>
                    <small class="form-text text-muted">Get this from <a href="https://t.me/BotFather" target="_blank">@BotFather</a> on Telegram.</small>
                </div>
                
                <div class="mb-3">
                    <label for="telegram_bot_username" class="form-label">Bot Username</label>
                    <div class="input-group">
                        <span class="input-group-text">@</span>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="telegram_bot_username" 
                            name="telegram_bot_username" 
                            value="{{ \App\Models\Setting::get('telegram_bot_username', 'YourBotUsername') }}" 
                            placeholder="YourBotUsername"
                        >
                    </div>
                    <small class="form-text text-muted">This is used to generate referral links for Telegram. Do not include the @ symbol.</small>
                </div>
                
                <div class="form-check mb-3">
                    <input type="hidden" name="auto_login" value="0">
                    <input class="form-check-input" type="checkbox" id="auto_login" name="auto_login" value="1" {{ $autoLogin ? 'checked' : '' }}>
                    <label class="form-check-label" for="auto_login">
                        Auto-login users when opening app in Telegram
                    </label>
                </div>
                
                <hr class="my-4">
                
                <h5>Proxy Settings (for hosting with connection issues)</h5>
                
                <div class="form-check mb-3">
                    <input type="hidden" name="use_proxy" value="0">
                    <input class="form-check-input" type="checkbox" id="use_proxy" name="use_proxy" value="1" {{ config('services.telegram.use_proxy') ? 'checked' : '' }}>
                    <label class="form-check-label" for="use_proxy">
                        Use proxy for Telegram API requests
                    </label>
                    <div class="form-text">Enable this if you're experiencing connection issues with the Telegram API on your hosting.</div>
                </div>
                
                <div class="mb-3" id="proxy-settings" style="{{ config('services.telegram.use_proxy') ? '' : 'display: none;' }}">
                    <label for="proxy_url" class="form-label">Proxy URL</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="proxy_url" 
                        name="proxy_url" 
                        value="{{ config('services.telegram.proxy_url') }}" 
                        placeholder="http://proxy-server:port"
                    >
                    <div class="form-text">Format examples: http://proxy-server:8080, socks5://username:password@proxy-server:1080</div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">Save Configuration</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-robot me-1"></i>
                    Bot Status
                </div>
                <div class="card-body" id="bot-info-container">
                    @if ($botInfo)
                        <div class="alert alert-success">
                            <strong>✅ Bot is connected successfully!</strong>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Bot ID</th>
                                        <td>{{ $botInfo['id'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Bot Name</th>
                                        <td>{{ $botInfo['first_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Username</th>
                                        <td>@{{ $botInfo['username'] }}</td>
                                    </tr>
                                    @if (!empty($botInfo['can_join_groups']))
                                        <tr>
                                            <th>Can Join Groups</th>
                                            <td>{{ $botInfo['can_join_groups'] ? 'Yes' : 'No' }}</td>
                                        </tr>
                                    @endif
                                    @if (!empty($botInfo['can_read_all_group_messages']))
                                        <tr>
                                            <th>Can Read Group Messages</th>
                                            <td>{{ $botInfo['can_read_all_group_messages'] ? 'Yes' : 'No' }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @elseif($botError)
                        <div class="alert alert-danger">
                            <strong>❌ Connection error:</strong> {{ $botError }}
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <strong>⚠️ Bot not configured:</strong> Add your bot token and test the connection.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Mini App Setup Instructions
                </div>
                <div class="card-body">
                    <ol>
                        <li>Create a bot in <a href="https://t.me/BotFather" target="_blank">@BotFather</a> (if you haven't already).</li>
                        <li>Get your bot token and set it above.</li>
                        <li>In BotFather, use the <code>/mybots</code> command.</li>
                        <li>Select your bot and go to "Bot Settings" > "Menu Button".</li>
                        <li>Choose "Edit Menu Button URL" and enter: <code>{{ url('/') }}</code></li>
                        <li>To set up webhooks (optional):
                            <code>{{ $webhookUrl }}</code>
                        </li>
                    </ol>
                    <div class="alert alert-info">
                        <strong>Note:</strong> Your app must be accessible from the internet for Telegram webhooks to work.
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
        // Test connection button
        const testButton = document.getElementById('test-connection');
        const botTokenField = document.getElementById('bot_token');
        const botInfoContainer = document.getElementById('bot-info-container');
        
        // Proxy settings toggle
        const useProxyCheckbox = document.getElementById('use_proxy');
        const proxySettings = document.getElementById('proxy-settings');
        
        if (useProxyCheckbox && proxySettings) {
            useProxyCheckbox.addEventListener('change', function() {
                proxySettings.style.display = this.checked ? 'block' : 'none';
            });
        }
        
        if (testButton && botTokenField) {
            testButton.addEventListener('click', function() {
                // Display loading state
                testButton.disabled = true;
                testButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Testing...';
                
                // Get token
                const token = botTokenField.value.trim();
                
                if (!token) {
                    alert('Please enter a bot token first');
                    testButton.disabled = false;
                    testButton.innerText = 'Test Connection';
                    return;
                }
                
                // Send request
                fetch('{{ route("admin.telegram.config.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        bot_token: token
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Create success message
                        const result = data.data;
                        
                        botInfoContainer.innerHTML = `
                            <div class="alert alert-success">
                                <strong>✅ Bot is connected successfully!</strong>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Bot ID</th>
                                            <td>${result.id}</td>
                                        </tr>
                                        <tr>
                                            <th>Bot Name</th>
                                            <td>${result.first_name}</td>
                                        </tr>
                                        <tr>
                                            <th>Username</th>
                                            <td>@${result.username}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        `;
                    } else {
                        // Display error
                        botInfoContainer.innerHTML = `
                            <div class="alert alert-danger">
                                <strong>❌ Connection error:</strong> ${data.message}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    botInfoContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <strong>❌ Request failed:</strong> ${error.message}
                        </div>
                    `;
                })
                .finally(() => {
                    // Reset button state
                    testButton.disabled = false;
                    testButton.innerText = 'Test Connection';
                });
            });
        }
    });
</script>
@endpush 