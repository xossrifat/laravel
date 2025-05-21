@extends('layouts.app')
@section('title', 'Account Settings')

@section('content')
<div class="mobile-header">
    @include('user.settings.settings')
    @include('layouts.banner')
</div>
<div class="container">
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
        
        <!-- Password Section 
        <div class="section">
            <div class="section-header">
                <span>Password</span>
                <a href="#" onclick="togglePasswordModal()" class="edit">✏️ CHANGE</a>
            </div>
            <p>Create or update your password.</p>
        </div> -->
        
        <!-- Payout Details -->
        <div class="section">
            <div class="section-header">
                <span>Payout Details</span>
                <a href="#" onclick="togglePayoutModal()" class="edit">✏️ EDIT</a>
            </div>
            <p>Provide your PayPal email address to complete withdrawals from your account.</p>
            @if(auth()->user()->payout_email)
                <p class="email">{{ auth()->user()->payout_email }}</p>
            @else
                <p class="missing-info">No payout information provided yet.</p>
            @endif
        </div>
        
        <!-- Profile Data -->
        <div class="section">
            <div class="section-header">
                <span>Update Profile Data</span>
                <a href="#" onclick="toggleProfileModal()" class="edit">✏️ EDIT</a>
            </div>
            <p>Kindly complete your profile information. This is required in order to gain access to features like online payments and withdrawals.</p>
            <div class="profile-info">
                <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <p><strong>Address:</strong> {{ auth()->user()->address ?? 'Not provided' }}</p>
            </div>
        </div>
        
        <!-- Email Verification 
        <div class="section">
            <div class="section-header">
                <span>Email Verification</span>
                <a href="{{ route('verification.send') }}" class="edit">✏️ VERIFY</a>
            </div>
            <p>Your email address <span class="email">{{ auth()->user()->email }}</span> is unverified. Click to resend the verification link.</p>
        </div> -->
        
        <!-- Account Deletion 
        <div class="section delete-section">
            <div class="section-header">
                <span>Deleting Profile: <span class="email-delete">{{ auth()->user()->email }}</span></span>
                <a href="#" onclick="confirmAccountDeletion()" class="delete">❌ CONFIRM</a>
            </div>
            <p>By clicking Confirm you have agreed to delete your profile completely.</p>
        </div> -->
        @include('layouts.banner')
    </main>
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

<!-- Profile Update Modal -->
<div id="profileModal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Update Profile Information</h3>
            <span class="close-modal" onclick="toggleProfileModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3">{{ auth()->user()->address }}</textarea>
                </div>
                <div class="form-actions">
                    <button type="button" onclick="toggleProfileModal()" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-update">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payout Details Modal -->
<div id="payoutModal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Update Payout Details</h3>
            <span class="close-modal" onclick="togglePayoutModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form action="{{ route('user.payout.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="payout_email">PayPal Email</label>
                    <input type="email" id="payout_email" name="payout_email" value="{{ auth()->user()->payout_email }}" required>
                </div>
                <div class="form-actions">
                    <button type="button" onclick="togglePayoutModal()" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-update">Update Payout Details</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body {
  margin: 0;
  font-family: 'Roboto', sans-serif;
  background-color: #f6f7f9;
  color: #333;
}

.container {
  display: flex;
        min-height: 100vh;
        max-width: 100%;
  margin: 0;
  padding: 0;
}

.sidebar {
  width: 260px;
  background-color: #fff;
  padding: 20px;
  border-right: 1px solid #ddd;
}

.balance-box {
  text-align: center;
  padding: 15px;
  border: 1px solid #eee;
  border-radius: 10px;
  background: #fafafa;
}

.icon {
  width: 50px;
  height: 40px;
  background: #ccc;
  margin: 0 auto 10px;
  border-radius: 6px;
}

.amount {
  font-size: 24px;
  font-weight: bold;
}

.coins {
  font-size: 12px;
  color: #666;
}

.balance-label {
  font-size: 13px;
  margin-top: 4px;
  color: #aaa;
}

.buttons {
  margin-top: 10px;
}

.btn {
  padding: 8px 16px;
  font-size: 12px;
  margin: 5px 5px 0 0;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  display: inline-block;
  text-decoration: none;
}

.withdraw {
  background-color: #5d4fff;
  color: #000;
}

.deposit {
  background-color: #5d4fff;
  color: #fff;
}
html.dark .section {
  background-color:rgb(27, 27, 27) !important;
}
.help-box {
  margin-top: 40px;
  text-align: center;
}

.help-title {
  font-size: 18px;
  font-weight: bold;
}

.help-text {
  font-size: 13px;
  margin: 10px 0;
  color: #777;
}

.chat-btn {
  background-color: #5d4fff;
  color: #000;
  padding: 10px 20px;
  border: none;
  font-weight: bold;
  border-radius: 5px;
  cursor: pointer;
  display: inline-block;
  text-decoration: none;
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

.edit {
  color: #5d4fff;
  font-size: 13px;
  text-decoration: none;
}

.delete-section .delete {
  color: red;
  font-size: 13px;
  text-decoration: none;
}

.email {
  color: #0077cc;
  font-weight: bold;
}

.email-delete {
  color: orange;
}

.profile-info {
  margin-top: 10px;
  font-size: 14px;
}

.verified-badge {
  background-color: #4CAF50;
  color: white;
  padding: 3px 8px;
  border-radius: 12px;
  font-size: 12px;
  margin-left: 10px;
}

.unverified-badge {
  background-color: #f44336;
  color: white;
  padding: 3px 8px;
  border-radius: 12px;
  font-size: 12px;
  margin-left: 10px;
}

.warning {
  color: #f44336;
  font-size: 13px;
  margin-top: 5px;
}

.missing-info {
  color: #f44336;
  font-style: italic;
  font-size: 14px;
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
    
.form-group input, .form-group textarea {
        width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-sizing: border-box;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 20px;
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

.btn-update {
  background-color: #5d4fff;
  color: white;
        border: none;
  padding: 8px 15px;
  border-radius: 5px;
  cursor: pointer;
}
.menu-list {
  margin: 30px 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.menu-btn {
  background-color: #fff;
  border: 1px solid #eee;
  padding: 10px;
  font-size: 14px;
  text-align: left;
  border-radius: 6px;
        cursor: pointer;
  transition: all 0.2s ease;
}

.menu-btn:hover {
  background-color: #f7f7f7;
}

.menu-btn.logout {
  color: red;
  font-weight: bold;
  border-color: red;
}

/* Mobile responsive styles */
@media (max-width: 768px) {
    .mobile-content {
        padding: 10px !important;
        margin-top: 20px;
    }
    
    .section {
        padding: 15px;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .modal-content {
        width: 95%;
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-cancel, .btn-update {
        width: 100%;
        margin-bottom: 10px;
    }
    
    .form-group input, 
    .form-group textarea {
        font-size: 16px; /* Prevent zoom on iOS */
        padding: 12px;
    }
    
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

@push('scripts')
<script>
    function togglePasswordModal() {
        const modal = document.getElementById('passwordModal');
        modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
    }
    
    function toggleProfileModal() {
        const modal = document.getElementById('profileModal');
        modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
    }
    
    function togglePayoutModal() {
        const modal = document.getElementById('payoutModal');
        modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
    }
    
    function confirmAccountDeletion() {
        if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
            window.location.href = '{{ route("user.account.delete") }}';
        }
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
@endpush
@endsection 