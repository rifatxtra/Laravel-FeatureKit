<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Under Maintenance | {{ \App\Models\Setting::get('app_name', config('app.name')) }}</title>
    <!-- Use Tailwind CSS CDN for the maintenance page to avoid asset issues during maintenance -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#0f172a] text-white flex items-center justify-center min-h-screen p-6">
    <div class="max-w-xl w-full text-center space-y-8 animate-in fade-in zoom-in duration-700">
        <!-- Logo -->
        <div class="flex justify-center">
            <img src="{{ \App\Models\Setting::get('app_logo', '/logo.png') }}" 
                 alt="Logo" 
                 class="h-20 w-auto rounded-xl shadow-2xl shadow-blue-500/20"
                 onerror="this.style.display='none'; this.nextSibling.style.display='block';">
            <div class="hidden text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-500">
                {{ \App\Models\Setting::get('app_name', 'Feature Kit') }}
            </div>
        </div>

        <!-- Content -->
        <div class="space-y-4">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight">
                Enhancing Your Experience
            </h1>
            <p class="text-gray-400 text-lg md:text-xl max-w-md mx-auto leading-relaxed">
                We're currently performing some scheduled maintenance to bring you new features. We'll be back shortly!
            </p>
        </div>

        <!-- Progress Indicator -->
        <div class="relative pt-4">
            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-800">
                <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 w-2/3 animate-pulse"></div>
            </div>
            <div class="flex justify-between text-xs font-semibold text-gray-500 uppercase tracking-widest">
                <span>Update in progress</span>
                <span>Estimate: {{ \App\Models\Setting::get('maintenance_duration', '15 mins') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="pt-12 border-t border-gray-800 flex flex-col items-center space-y-4">
            <p class="text-sm text-gray-500">Need immediate assistance?</p>
            <a href="mailto:{{ \App\Models\Setting::get('email_support', 'support@rifatxtra.com') }}" 
               class="px-6 py-2 rounded-full border border-gray-700 hover:border-blue-500 hover:bg-blue-500/10 transition-all font-medium">
                Contact Support
            </a>
        </div>

        <p class="text-[10px] text-gray-700 uppercase tracking-[0.2em] pt-8">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::get('app_name', 'Laravel Feature Kit') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
