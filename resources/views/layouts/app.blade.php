<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Restaurant POS - System</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo.png') }}">
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.5/dist/flowbite.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css"/>
        @stack('style')
        <!-- font-awesome icons -->
        <script src="https://kit.fontawesome.com/2d49de291b.js" crossorigin="anonymous"></script>
        
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

        <!-- POS -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <script src="https://unpkg.com/idb/build/iife/index-min.js"></script>
        <script src="{{ asset('js/script.js') }}"></script>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100"> 
            <div class="md:flex bg-white">
                <div class="hidden md:block md:flex w-2/5 md:w-1/5 h-screen sticky text-gray-800 top-0 bg-sky-300 border-r hidden">
                    <div class="mx-auto py-5">
                        <ul>
                            <a href="{{ route('dashboard') }}"><li class="mb-4">					
                                <div class="flex justify-center text-gray-900 font-semibold">
                                    Restaurant POS
                                </div>
                            </li></a>
                            <a class="{{ (request()->segment(1) == 'dashboard') ? 'bg-white border-white': '' }} py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('dashboard') }}">
                                <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 bg-center stroke-0 text-center xl:p-2.5">
                                    <img src="{{ asset('assets/home.svg') }}" alt="Home Icon" class="w-5 h-5">  
                                </div>
                                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
                            </a>
                            <a class="{{ (request()->segment(1) == 'cashier') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('cashier.index') }}">
                                <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                    <img src="{{ asset('assets/cash.svg') }}" alt="Cashier Icon" class="w-5 h-5">   
                                </div>
                                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Cashier</span>
                            </a>
                            <a class="{{ (request()->segment(1) == 'sales') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('sales.index') }}">
                                <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                    <img src="{{ asset('assets/cart.svg') }}" alt="Sales Icon" class="w-5 h-5">   
                                </div>
                                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Sales</span>
                            </a>
                            <a class="{{ (request()->segment(1) == 'items') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('items.index') }}">
                                <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                    <img src="{{ asset('assets/store.svg') }}" alt="Items Icon" class="w-5 h-5">   
                                </div>
                                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Items</span>
                            </a>
                            <a class="{{ (request()->segment(1) == 'transactions') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('transactions.index') }}">
                                <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                    <img src="{{ asset('assets/fee.svg') }}" alt="Transaction Icon" class="w-5 h-5">   
                                </div>
                                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Transactions</span>
                            </a>
                            <a class="{{ (request()->segment(1) == 'profile') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('profile.index') }}">
                                <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                    <img src="{{ asset('assets/profile.svg') }}" alt="Profile Icon" class="w-5 h-5">   
                                </div>
                                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Profile</span>
                            </a>
                            <a class="{{ (request()->segment(1) == 'users') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('users.index') }}">
                                <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                    <img src="{{ asset('assets/users.svg') }}" alt="Users Icon" class="w-5 h-5">   
                                </div>
                                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Users</span>
                            </a>
                            <a class="{{ (request()->segment(1) == 'logout') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('logout') }}">
                                <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                    <img src="{{ asset('assets/logout.svg') }}" alt="Log Out Icon" class="w-5 h-5">   
                                </div>
                                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Log Out</span>
                            </a>        
                        </ul>
                    </div>
                </div>

                <div class="block md:hidden bg-gray-100">
                    <div class="bg-gray-300 text-black">
                        <div class="sticky container mx-auto px-6 py-4 flex justify-end items-center text-gray-900">                    
                            <div class="block md:hidden">
                                <button id="menu-toggle" class="text-black focus:outline-none">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                </button>
                            </div>
                        </div> 
                        <div id="mobile-menu" class="hidden md:hidden">
                            <nav class="flex flex-col items-center font-semibold text-black space-y-2 py-2">
                                <ul>
                                    <a href="{{ route('dashboard') }}"><li class="mb-4">					
                                        <div class="flex justify-center">
                                            <div class="flex justify-center text-gray-900 font-semibold">
                                                Restaurant POS
                                            </div>
                                        </div>
                                    </li></a>
                                    <a class="{{ (request()->segment(1) == 'dashboard') ? 'bg-white border-white': '' }} py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('dashboard') }}">
                                        <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                            <img src="{{ asset('assets/home.svg') }}" alt="Home Icon" class="w-5 h-5">  
                                        </div>
                                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
                                    </a>
                                    <a class="{{ (request()->segment(1) == 'cashier') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('cashier.index') }}">
                                        <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                            <img src="{{ asset('assets/cash.svg') }}" alt="Cashier Icon" class="w-5 h-5">   
                                        </div>
                                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Cashier</span>
                                    </a>
                                    <a class="{{ (request()->segment(1) == 'sales') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('sales.index') }}">
                                        <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                            <img src="{{ asset('assets/cart.svg') }}" alt="Sales Icon" class="w-5 h-5">   
                                        </div>
                                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Sales</span>
                                    </a>
                                    <a class="{{ (request()->segment(1) == 'items') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('items.index') }}">
                                        <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                            <img src="{{ asset('assets/store.svg') }}" alt="Items Icon" class="w-5 h-5">   
                                        </div>
                                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Items</span>
                                    </a>
                                    <a class="{{ (request()->segment(1) == 'transactions') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('transactions.index') }}">
                                        <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                            <img src="{{ asset('assets/fee.svg') }}" alt="Transaction Icon" class="w-5 h-5">   
                                        </div>
                                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Transactions</span>
                                    </a>
                                    <a class="{{ (request()->segment(1) == 'profile') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('profile.index') }}">
                                        <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                            <img src="{{ asset('assets/profile.svg') }}" alt="Profile Icon" class="w-5 h-5">   
                                        </div>
                                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Profile</span>
                                    </a>
                                    <a class="{{ (request()->segment(1) == 'users') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('users.index') }}">
                                        <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                            <img src="{{ asset('assets/users.svg') }}" alt="Users Icon" class="w-5 h-5">   
                                        </div>
                                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Users</span>
                                    </a>
                                    <a class="{{ (request()->segment(1) == 'logout') ? 'bg-white border-white': '' }} mt-4 py-2 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-sky-200 px-4 font-semibold text-slate-700 transition-colors hover:bg-sky-100 hover:border-white" href="{{ route('logout') }}">
                                        <div class="bg-gradient-to-tl from-sky-800 to-sky-400 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                            <img src="{{ asset('assets/logout.svg') }}" alt="Log Out Icon" class="w-5 h-5">   
                                        </div>
                                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Log Out</span>
                                    </a>         
                                </ul>
                            </nav>                
                        </div>               
                    </div>
                </div> 
                
                <main class="min-h-screen w-full bg-white border-l" style="overflow: auto;"> 
                        
                    @yield('bodycontent')		
                </main>                
            </diV>            
        </div> 
              
        <script src="https://unpkg.com/flowbite@1.5.5/dist/flowbite.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        @stack('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('menu-toggle').addEventListener('click', function() {
                    var menu = document.getElementById('menu');
                    var mobileMenu = document.getElementById('mobile-menu');
                    if (mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.remove('hidden');
                    } else {
                        mobileMenu.classList.add('hidden');
                    }
                });
            });
        </script>
    </body>
</html>