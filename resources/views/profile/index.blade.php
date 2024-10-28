@extends('layouts.app')
@section('bodycontent')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h5 class="font-bold text-center text-black">Profile</h5><br>
                @if (session('success'))
                    <div class="text-black m-2 p-2 bg-yellow-200">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('err'))
                    <div class="text-black m-2 p-2 bg-red-200">
                        {{ session('err') }}
                    </div>
                @endif                     
                <h5 class="font-bold text-black mt-4">Change Password</h5><br>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')                    
                    <input type="hidden" name="userid"  id="userid">
                    <div>
                        <label for="password">Current  Password</label><br>
                        <div class="relative w-96">
                            <input type="password" name="current_password" id="current_password" autocomplete="new-password" class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 pr-10 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="password" required>
                            <button type="button" id="togglePassword1" class="hidden absolute inset-y-0 right-0 flex items-center pr-3 text-gray-600 focus:outline-none">
                                <i id="current_ico" class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    @error('current_password') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <div>
                        <label for="password">New Password</label><br>
                        <div class="relative w-96">
                            <input type="password" name="password" id="addpassword" autocomplete="new-password" class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 pr-10 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="password" required>
                            <button type="button" id="togglePassword" class="hidden absolute inset-y-0 right-0 flex items-center pr-3 text-gray-600 focus:outline-none">
                                <i id="new_ico" class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">*The password must contain at least one uppercase letter, one number, and one special character.</p>
                    @error('password') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <div>
                        <label for="confirm_password">Confirm Password</label><br>
                        <input type="password" name="confirm_password" id="cmpassword" autocomplete="new-password" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="confirm password" required>        
                        <span id='passwordcheck'></span>
                    </div>
                    @error('confirm_password') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br><br>                   
                    <button type="submit" class="passwordvalid inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('addpassword');
            const passwordIcon = document.getElementById('new_ico');
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
        document.getElementById('togglePassword1').addEventListener('click', function () {
            const currentPasswordField = document.getElementById('current_password');
            const currentPasswordIcon = document.getElementById('current_ico');
            if (currentPasswordField.type === 'password') {
                currentPasswordField.type = 'text';
                currentPasswordIcon.classList.remove('fa-eye');
                currentPasswordIcon.classList.add('fa-eye-slash');
            } else {
                currentPasswordField.type = 'password';
                currentPasswordIcon.classList.remove('fa-eye-slash');
                currentPasswordIcon.classList.add('fa-eye');
            }
        });
    </script>
    <script>
        $('#addpassword, #cmpassword, #current_password').on('keyup', function () { 
        var password = $('#addpassword').val();
        if (password.length >= 1){
            document.getElementById('togglePassword').style.display = "block";
        }
        else{
            document.getElementById('togglePassword').style.display = "none";
        }
        var current_password = $('#current_password').val();
        if (current_password.length >= 1){
            document.getElementById('togglePassword1').style.display = "block";
        }
        else{
            document.getElementById('togglePassword1').style.display = "none";
        }
        if ($('#addpassword').val() == $('#cmpassword').val()) {
            $('#passwordcheck').html('');
            $(".passwordvalid").attr('disabled', false);
        }
        else if($('#cmpassword').val() == ''){
            $('#passwordcheck').html('');
        }
        else { 
            $('#passwordcheck').html('Passwords Not Matching').css('color', 'red');
            $(".passwordvalid").attr('disabled', true);
        }
        if ($('#addpassword').val() == '' && $('#cmpassword').val() == '') {
            $('#passwordcheck').html('');
        }  
        });
    </script>
@endpush
@endsection