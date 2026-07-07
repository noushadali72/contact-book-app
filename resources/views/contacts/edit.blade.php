@extends("layouts.main")

@section("title")
<title>Edit Contact</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('styles')
    <style>
        .error{
            color:red;
        }
        .required{
            color:red;
        }
    </style>
@endsection
@section("content")
    <div class="container mt-5">
        <div class="card p-4">
            <h2>Edit Contact</h2>

            <div id="message"></div>
            <form id="form">
                <input type="hidden" name="id" value="{{ $contact->id }}">
                <div class="mb-3">
                    <label for="name" class="form-label">Name<sup class="required">*</sup>:</label>
                    <input id="name" name="name" type="text" class="form-control" value="{{ $contact->name }}">
                    <span class="error" id="nameErr"></span>
            
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email<sup class="required">*</sup>:</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ $contact->email }}">
                    <span class="error" id="emailErr"></span>
                   
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone<sup class="required">*</sup>:</label>
                    <input type="text" name="phone" class="form-control" id="phone" value="{{ $contact->phone }}">
                    <span class="error" id="phoneErr"></span>
                   
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address<sup class="required">*</sup>:</label>
                    <input type="text" name="address" class="form-control" id="address" value="{{ $contact->address }}">
                    <span class="error" id="addressErr"></span>
                  
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes<sup class="required">*</sup>:</label>
                    <textarea name="notes" id="notes" cols="30" rows="10" class="form-control">{{ $contact->notes }}</textarea>
                    <span class="error" id="notesErr"></span>
                   
                </div>

                <div class="mb-3">
                    <label for="group" class="form-label">Group<sup class="required">*</sup>:</label>
                    <select name="group_id" id="group" class="form-control">
                        <option value="" selected></option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" {{ $contact->group_id==$group->id?"selected":"" }}>{{ $group->name }}</option>
                        @endforeach
                       
                    </select>
                 <span class="error" id="groupErr"></span>
                </div>

                <button class="btn btn-primary" id="submit" type="submit">Submit</button>

            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){

            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var phoneRegex = /^\+?[1-9]\d{1,14}$/;
            var isValid = true;

            function validateEmail(){
                var email = $("#email").val().trim();
                if(email==""){
                    $("#emailErr").text("Email is Required!");
                    isValid = false;
                }else if(!emailRegex.test(email)){
                    $("#emailErr").text("Email must be valid!");
                    isValid = false;
                }else{
                    
                    $("#emailErr").text("");
                }
            }

            function validatePhone(){
                var phone = $("#phone").val().trim();

                if(phone==""){
                    $("#phoneErr").text("Phone no is Required!");
                    isValid = false;
                }else if(!phoneRegex.test(phone)){
                    $("#phoneErr").text("Phone no must be valid!");
                    isValid = false;
                }else{
                    $("#phoneErr").text("");
                }
            }

            function validateName(){
                var name = $("#name").val().trim();

                if(name==""){
                    $("#nameErr").text("Name is Required!");
                    isValid = false;
                }else{
                    $("#nameErr").text("");
                }
            }

               function validateNotes(){
                var notes = $("#notes").val().trim();

                if(notes==""){
                    $("#notesErr").text("Notes is Required!");
                    isValid = false;
                }else{
                    $("#notesErr").text("");
                }
            }

               function validateGroup(){
                var group = $("#group").val().trim();

                if(group==""){
                    $("#groupErr").text("Group is Required!");
                    isValid = false;
                }else{
                    $("#groupErr").text("");
                }
            }

            function validateAddress(){
                var address = $("#address").val().trim();

                if(address==""){
                    $("#addressErr").text("Address is Required!");
                    isValid = false;
                }else{
                    $("#addressErr").text("");
                }
            }


            // $("#name").on("input blur",validateName);
            // $("#email").on("input blur",validateEmail);
            // $("#phone").on("input blur",validatePhone);
            // $("#notes").on("input blur",validateNotes);
            // $("#group").on("input blur",validateGroup);
            // $("#address").on("input blur",validateAddress);


            $("#form").on("submit",function(e){
                e.preventDefault();
                isValid = true;
                // validateName();
                // validateEmail();
                // validatePhone();
                // validateNotes();
                // validateGroup();
                // validateAddress();

                if(isValid){
                    let form = document.getElementById("form");
                    let formdata = new FormData(form);
                    $.ajax({
                        url:'{{ route('contacts.update') }}',
                        type:'PUT',
                        processData:false,
                        contentType: false,
                        data: formdata,
                        headers: {
                                   
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                        success: function(res){
                            console.log(res);
                                $("#nameErr").text("");
                                $("#emailErr").text("");
                                $("#phoneErr").text("");
                                $("#addressErr").text("");
                                $("#notesErr").text("");
                                $("#groupErr").text("");

                                $("#message").html(`<div class="alert alert-success" role="alert">
                                ${res.message}
                                    </div>`);
                        },
                        error: function(xhr,status,error){
                          
                            if(xhr.responseJSON && xhr.responseJSON.errors){
                                
                                var errors = xhr.responseJSON.errors;
                                $("#nameErr").text(errors.name||"");
                                $("#emailErr").text(errors.email||"");
                                $("#phoneErr").text(errors.phone||"");
                                $("#addressErr").text(errors.address||"");
                                $("#notesErr").text(errors.notes||"");
                                $("#groupErr").text(errors.group_id||"");
                        
                            }else{
                               
                                $("#message").html(`<div class="alert alert-danger" role="alert">
                                    Unable to Update Contact Try Again!
                                </div>`);
                            }
                         
                        }
                    });
                }
            })

        });

    </script>
@endsection