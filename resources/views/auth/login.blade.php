<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Bitware POS - Login</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo.png') }}">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- font-awesome icons -->
        <script src="https://kit.fontawesome.com/2d49de291b.js" crossorigin="anonymous"></script>

    </head>
    <body>
        <div class="min-w-screen min-h-screen bg-gradient-to-r from-gray-800 to-gray-400 flex items-center justify-center px-5 py-5">
            <div class="bg-gray-100 text-gray-500 rounded-3xl shadow-xl w-full overflow-hidden" style="max-width:1000px">
                <div class="md:flex w-full">
                    <div class="hidden md:flex w-1/2 bg-white py-10 px-10 justify-center">
                        <div>                            
                            <img src="{{ asset('assets/form1.png') }}" alt="form">
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 py-10 px-5 md:px-10">
                        <div class="text-center mb-10">
                            <div class="flex items-center justify-center">
                                <img width="150px" height="150px" src="{{ asset('assets/logo.png') }}" alt="logo"/>
                            </div>
                            <h1 class="font-bold text-3xl text-gray-900">SIGN IN</h1>
                        </div>
                        @if (session('status'))
                            <div class="text-black m-2 p-4 bg-green-200">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="text-black m-2 p-4 bg-yellow-200">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('delete'))
                            <div class="text-black m-2 p-4 bg-red-200">
                                {{ session('delete') }}
                            </div>
                        @endif
                        <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                            <div>
                                <div class="flex -mx-3">
                                    <div class="w-full px-3 mb-2">
                                        <label for="" class="text-xs font-semibold px-1">Email</label>
                                        <div class="flex">
                                            <div class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center"><i class="mdi mdi-email-outline text-gray-400 text-lg"></i></div>
                                            <input type="email" name="email" id="email" class="w-full -ml-10 pl-10 pr-3 py-2 rounded-lg border-2 border-gray-200 outline-none focus:border-indigo-500" placeholder="email" required>
                                        </div>
                                    </div>
                                </div>
                                @error('email') <span class="text-red-500 error mb-2">{{ $message }}</span><br> @enderror
                                <div class="flex -mx-3">
                                    <div class="w-full px-3 mb-2">
                                        <label for="password" class="text-xs font-semibold px-1">Password</label>
                                        <div class="relative flex">
                                            <div class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center">
                                                <i class="mdi mdi-lock-outline text-gray-400 text-lg"></i>
                                            </div>
                                            <input type="password" name="password" id="password" class="w-full -ml-10 pl-10 pr-10 py-2 rounded-lg border-2 border-gray-200 outline-none focus:border-indigo-500" placeholder="password" required>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <button type="button" id="togglePassword" class="hidden text-gray-600 focus:outline-none">
                                                    <i class="fa-regular fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>                            
                                @error('password') <span class="text-red-500 error mb-2">{{ $message }}</span><br> @enderror
                                <div class="flex -mx-3 mt-4">
                                    <div class="w-full px-3 mb-5">
                                        <button type="submit" class="disabled:opacity-25 block w-full max-w-xs mx-auto bg-gray-800 hover:bg-gray-900 focus:bg-gray-800 text-white rounded-lg px-3 py-3 font-semibold">SIGN IN</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script>
            document.getElementById('togglePassword').addEventListener('click', function () {
                const passwordField = document.getElementById('password');
                const passwordIcon = this.querySelector('i');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    passwordIcon.classList.remove('fa-eye');
                    passwordIcon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    passwordIcon.classList.remove('fa-eye-slash');
                    passwordIcon.classList.add('fa-eye');
                }
            });
        </script>
        <script>
            $('#password').on('keyup', function () { 
                var password = $('#password').val();
                if (password.length >= 1){
                    document.getElementById('togglePassword').style.display = "block";
                }
                else{
                    document.getElementById('togglePassword').style.display = "none";
                }
            });
        </script>
    </body>
</html>