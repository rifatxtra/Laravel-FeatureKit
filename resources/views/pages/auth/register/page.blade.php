@extends('pages.auth.layout')
@section('content')

{{-- Back to Home --}}
<div class="mb-6 flex justify-between items-center">
    <a href="{{ url('/') }}"
       class="text-sm font-semibold text-primary hover:underline">
        ← Back to Home
    </a>
</div>

<form method="POST" action="{{ route('auth.register.register') }}" class="space-y-5">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Name --}}
        <div class="md:col-span-2">
            <label class="block text-sm font-bold text-surface-foreground mb-2 uppercase tracking-wider">
                Full Name
            </label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full px-5 py-4 border-2 rounded-2xl focus:ring-4 focus:ring-primary/10 outline-none
                   {{ $errors->has('name') ? 'border-error' : 'border-surface-muted focus:border-primary' }} bg-surface"
                   placeholder="John Doe">

            @error('name')
                <p class="mt-2 text-sm text-error font-bold">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-bold text-surface-foreground mb-2 uppercase tracking-wider">
                Email address
            </label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full px-5 py-4 border-2 rounded-2xl focus:ring-4 focus:ring-primary/10 outline-none
                   {{ $errors->has('email') ? 'border-error' : 'border-surface-muted focus:border-primary' }} bg-surface"
                   placeholder="name@example.com">

            @error('email')
                <p class="mt-2 text-sm text-error font-bold">{{ $message }}</p>
            @enderror
        </div>

        {{-- Phone --}}
        <div>
            <label class="block text-sm font-bold text-surface-foreground mb-2 uppercase tracking-wider">
                Phone Number
            </label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   class="w-full px-5 py-4 border-2 rounded-2xl focus:ring-4 focus:ring-primary/10 outline-none
                   {{ $errors->has('phone') ? 'border-error' : 'border-surface-muted focus:border-primary' }} bg-surface"
                   placeholder="+1 (555) 000-0000">

            @error('phone')
                <p class="mt-2 text-sm text-error font-bold">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="block text-sm font-bold text-surface-foreground mb-2 uppercase tracking-wider">
                Password
            </label>
            <input type="password" name="password"
                   class="w-full px-5 py-4 border-2 rounded-2xl focus:ring-4 focus:ring-primary/10 outline-none
                   {{ $errors->has('password') ? 'border-error' : 'border-surface-muted focus:border-primary' }} bg-surface"
                   placeholder="••••••••">

            @error('password')
                <p class="mt-2 text-sm text-error font-bold">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label class="block text-sm font-bold text-surface-foreground mb-2 uppercase tracking-wider">
                Confirm Password
            </label>
            <input type="password" name="password_confirmation"
                   class="w-full px-5 py-4 border-2 rounded-2xl focus:ring-4 focus:ring-primary/10 outline-none border-surface-muted focus:border-primary bg-surface"
                   placeholder="••••••••">
        </div>
    </div>

    {{-- Terms --}}
    <div class="flex items-start">
        <input type="checkbox" name="terms" class="w-5 h-5 mt-1 accent-primary">
        <label class="ml-3 text-sm font-semibold text-surface-muted-foreground">
            I agree to the
            <a href="#" class="text-primary font-bold hover:underline">Terms</a>
            and
            <a href="#" class="text-primary font-bold hover:underline">Privacy Policy</a>
        </label>
    </div>

    @error('terms')
        <p class="text-sm text-error font-bold">{{ $message }}</p>
    @enderror

    {{-- Submit --}}
    <button type="submit"
            class="w-full py-4 bg-primary text-primary-foreground rounded-2xl font-bold">
        Create Account
    </button>
</form>

<div class="pt-6 text-center border-t border-border mt-6">
    <p class="text-surface-muted-foreground font-semibold">
        Already have an account?
        <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">
            Sign In
        </a>
    </p>
</div>
@endsection