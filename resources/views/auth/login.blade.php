<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
    <div class="success-message">
        <i class="fas fa-check-circle"></i> {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input 
                id="email" 
                class="form-input" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="admin@kadangu.com"
            >
            @error('email')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input 
                id="password" 
                class="form-input"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="••••••••"
            >
            @error('password')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="checkbox-container">
            <input id="remember_me" type="checkbox" class="checkbox" name="remember">
            <label for="remember_me" class="checkbox-label">Remember me</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>

        <!-- Forgot Password -->
        @if (Route::has('password.request'))
        <div class="forgot-password">
            <a href="{{ route('password.request') }}">
                Forgot your password?
            </a>
        </div>
        @endif
    </form>
</x-guest-layout>
