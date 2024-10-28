@extends('layouts.app')
@section('bodycontent')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <a href="{{ route('users.index') }}" title="back" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" ><i class="fa-solid fa-arrow-left-long"></i></a><br><br>
                <h5 class="font-bold text-center text-black">New User</h5><br>                
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="fname">Enter First Name</label><br>
                        <input type="text" name="fname" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="first name" required>
                    </div>
                    @error('fname') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <div>
                        <label for="lname">Enter Last Name</label><br>
                        <input type="text" name="lname" autocomplete="off" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="last name" required>
                    </div>
                    @error('lname') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <div>
                        <label for="email">Enter Email</label><br>
                        <input type="text" name="email" id="email" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="email" required>
                    </div>
                    <p id="danger_alert1" class="text-sm text-red-500 mb-2" style="display:none;"></p>
                    @error('email') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <div>
                        <label for="password">Password</label><br>
                        <div class="relative w-96">
                            <input type="password" name="password" id="addpassword" autocomplete="new-password" class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 pr-10 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="password" required>
                            <button type="button" id="togglePassword" class="hidden absolute inset-y-0 right-0 flex items-center pr-3 text-gray-600 focus:outline-none">
                                <i class="fa-regular fa-eye"></i>
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
                    <br>
                    <button type="submit" class="passwordvalid disabled:opacity-25 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25">Save</button>                        
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#email').change(function() {
                document.getElementById('danger_alert1').style.display = "none";
                var email = $(this).val();
                if (email.length >= 3){
                    $.ajax({
                        type: 'GET',
                        url: '/users/email/check',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            "email": email
                        },
                        success: function(response) {
                            if (response.success) {
                                $(".passwordvalid").attr('disabled', false);

                            } else {
                                document.getElementById('danger_alert1').innerHTML = "Already Registered User!"
                                document.getElementById('danger_alert1').style.display = "block";
                                $(".passwordvalid").attr('disabled', true);
                            }


                        },
                        error: function(data) {
                            console.log('Something went wrong!');
                            document.getElementById('danger_alert1').innerHTML = "Already Registered User!"
                            document.getElementById('danger_alert1').style.display = "block";
                            $(".passwordvalid").attr('disabled', true);
                        }
                    });
                }
            });
        });
    </script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('addpassword');
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
        $('#addpassword, #cmpassword').on('keyup', function () { 
        var password = $('#addpassword').val();
        if (password.length >= 1){
            document.getElementById('togglePassword').style.display = "block";
        }
        else{
            document.getElementById('togglePassword').style.display = "none";
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