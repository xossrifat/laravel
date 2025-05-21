@extends('layouts.app')

@section('title', 'Privacy & Security Settings')

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
                <span>Account Security</span>
            </div>
            <p>Manage your account security settings</p>
            
            <div class="security-items">
                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="security-content">
                        <h3>Password</h3>
                        <p>It's a good idea to use a strong password that you don't use elsewhere</p>
                    </div>
                    <div class="security-action">
                        <a href="#" onclick="togglePasswordModal()" class="btn btn-sm btn-primary">Change</a>
                    </div>
                </div>
                
                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="security-content">
                        <h3>Two-Factor Authentication</h3>
                        <p>Add an extra layer of security to your account</p>
                    </div>
                    <div class="security-action">
                        <span class="status-badge coming-soon">Coming Soon</span>
                    </div>
                </div>
                
                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="security-content">
                        <h3>Login Activity</h3>
                        <p>Review where you're logged in</p>
                    </div>
                    <div class="security-action">
                        <a href="#" class="btn btn-sm btn-secondary disabled">View</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="section">
            <div class="section-header">
                <span>Privacy Controls</span>
            </div>
            <p>Manage how your information is used</p>
            
            <div class="privacy-controls">
                <div class="privacy-item">
                    <div class="privacy-toggle">
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="privacy-content">
                        <h3>Profile Visibility</h3>
                        <p>Allow other users to see your profile information</p>
                    </div>
                </div>
                
                <div class="privacy-item">
                    <div class="privacy-toggle">
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="privacy-content">
                        <h3>Activity Status</h3>
                        <p>Show when you're active in the app</p>
                    </div>
                </div>
                
                <div class="privacy-item">
                    <div class="privacy-toggle">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="privacy-content">
                        <h3>Data Usage</h3>
                        <p>Allow us to collect anonymous usage data to improve our services</p>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-update disabled">Save Privacy Settings</button>
                <p class="coming-soon">Coming soon</p>
            </div>
        </div>
        
        <div class="section">
            <div class="section-header">
                <span>Data & Privacy</span>
            </div>
            <p>Manage your personal data</p>
            
            <div class="data-controls">
                <a href="#" class="data-control-item">
                    <div class="data-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="data-content">
                        <h3>Download Your Data</h3>
                        <p>Get a copy of your personal data</p>
                    </div>
                    <div class="data-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                
                <a href="#" class="data-control-item">
                    <div class="data-icon">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <div class="data-content">
                        <h3>Delete Account</h3>
                        <p>Permanently delete your account and all data</p>
                    </div>
                    <div class="data-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>
    </main>
    @include('layouts.banner')
</div>

<!-- Password Change Modal -->
<div id="passwordModal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Change Password</h3>
            <span class="close-modal" onclick="togglePasswordModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form action="{{ route('user.password.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
                <div class="form-actions">
                    <button type="button" onclick="togglePasswordModal()" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-update">Update Password</button>
                </div>
            </form>
        </div>
    </div>
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
  margin-bottom: 10px;
}

.security-items, .privacy-controls, .data-controls {
  margin-top: 20px;
}

.security-item, .privacy-item {
  display: flex;
  align-items: center;
  padding: 15px;
  border: 1px solid #eee;
  border-radius: 8px;
  margin-bottom: 15px;
}
html.dark .section {
  background-color:rgb(27, 27, 27) !important;
}
.security-icon, .privacy-toggle, .data-icon {
  margin-right: 15px;
  width: 40px;
  height: 40px;
  background-color: #f7f7f7;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #f8b500;
  font-size: 18px;
}

.security-content, .privacy-content, .data-content {
  flex: 1;
}

.security-content h3, .privacy-content h3, .data-content h3 {
  font-size: 16px;
  margin: 0 0 5px 0;
}

.security-content p, .privacy-content p, .data-content p {
  font-size: 13px;
  color: #666;
  margin: 0;
}

.security-action {
  margin-left: 15px;
}

.btn {
  padding: 8px 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  display: inline-block;
  text-decoration: none;
  text-align: center;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 12px;
}

.btn-primary {
  background-color: #f7c700;
  color: #000;
}

.btn-secondary {
  background-color: #f0f0f0;
  color: #333;
}

.btn-disabled, .btn.disabled {
  background-color: #e0e0e0;
  color: #666;
  cursor: not-allowed;
}

.status-badge {
  font-size: 12px;
  padding: 4px 8px;
  border-radius: 12px;
  background-color: #f0f0f0;
}

.status-badge.coming-soon {
  background-color: #f0f0f0;
  color: #666;
}

/* Toggle Switch */
.switch {
  position: relative;
  display: inline-block;
  width: 46px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .4s;
}

input:checked + .slider {
  background-color: #f7c700;
}

input:focus + .slider {
  box-shadow: 0 0 1px #f7c700;
}

input:checked + .slider:before {
  transform: translateX(22px);
}

.slider.round {
  border-radius: 24px;
}

.slider.round:before {
  border-radius: 50%;
}

.data-control-item {
  display: flex;
  align-items: center;
  padding: 15px;
  border: 1px solid #eee;
  border-radius: 8px;
  margin-bottom: 15px;
  text-decoration: none;
  color: inherit;
  transition: background-color 0.2s;
}

.data-control-item:hover {
  background-color: #f9f9f9;
}

.data-arrow {
  color: #ccc;
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

.btn-cancel {
  background-color: #f1f1f1;
  color: #333;
  border: none;
  padding: 8px 15px;
  margin-right: 10px;
  border-radius: 5px;
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

/* Modal Styles */
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1000;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1001;
}

.modal-content {
  position: relative;
  background-color: #fff;
  margin: 10% auto;
  padding: 0;
  width: 80%;
  max-width: 500px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  z-index: 1002;
}

.modal-header {
  padding: 15px 20px;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  margin: 0;
  font-size: 18px;
}

.close-modal {
  color: #aaa;
  font-size: 24px;
  font-weight: bold;
  cursor: pointer;
}

.modal-body {
  padding: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.form-group input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-sizing: border-box;
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

<script>
function togglePasswordModal() {
    const modal = document.getElementById('passwordModal');
    modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
}

// Close modals when clicking outside
window.onclick = function(event) {
    const modals = document.getElementsByClassName('modal');
    for (let i = 0; i < modals.length; i++) {
        if (event.target === modals[i].getElementsByClassName('modal-overlay')[0]) {
            modals[i].style.display = 'none';
        }
    }
}
</script>
@endsection