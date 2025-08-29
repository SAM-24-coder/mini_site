<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">   
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            padding: 1rem 0;
        }
        .pagination > * {
            margin: 0 0.25rem;
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.25rem;
            color: #4a5568;
            text-decoration: none;
            transition: all 0.2s;
        }
        .pagination > *:hover {
            background-color: #f7fafc;
            border-color: #cbd5e0;
        }
        .pagination .active {
            background-color: #4299e1;
            color: white;
            border-color: #4299e1;
        }
        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <!-- Top bar -->
    <div class="flex items-center justify-between px-6 py-2 bg-blue-600 sm:py-4 relative overflow-visible">
        <!-- Hamburger button inside header -->
        <div class="hamburger-container absolute left-4 top-1/2 transform -translate-y-1/2 z-50 lg:hidden">
            <button id="mobile-menu-btn" class="p-2 text-white bg-gray-800 rounded-lg shadow">
                ☰
            </button>
        </div>
        <!-- DASHBOARD TEXT CORRIGÉ -->
        <div class="ml-10 text-lg font-semibold text-white lg:ml-0">
            <a href="{{ route('admin.dashboard') }}">DASHBOARD</a>
        </div>
        
        <!-- Profile section avec popup -->
        <div class="relative ml-auto">
            <div id="profile-btn" class="flex items-center justify-center w-10 h-10 bg-white rounded-full cursor-pointer bg-opacity-20 hover:bg-opacity-30 transition-all duration-200">
                <span class="text-xl text-white">👤</span>
            </div>
            <!-- Popup Menu -->
            <div id="profile-popup" class="absolute right-0 mt-2 w-64 sm:w-72 bg-white rounded-lg shadow-xl border border-gray-200 z-50 hidden">
                <div class="absolute -top-2 right-4 w-4 h-4 bg-white border-l border-t border-gray-200 transform rotate-45"></div>
                <div class="py-2">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
                                <span class="text-xl text-blue-600">👤</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">John Doe</p>
                                <p class="text-xs text-gray-500">john.doe@example.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-1">
                        <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                            <span>Mise à jour de mon profil</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                            <span>Accueil</span>
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <a href="#" class="flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150">
                            <span>Sign Out</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </div>

    <script>
        // Script pour le popup profile
        document.addEventListener('DOMContentLoaded', function() {
            const profileBtn = document.getElementById('profile-btn');
            const profilePopup = document.getElementById('profile-popup');
            
            if (profileBtn && profilePopup) {
                profileBtn.addEventListener('click', function() {
                    profilePopup.classList.toggle('hidden');
                });
                
                // Fermer le popup en cliquant ailleurs
                document.addEventListener('click', function(event) {
                    if (!profileBtn.contains(event.target) && !profilePopup.contains(event.target)) {
                        profilePopup.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>