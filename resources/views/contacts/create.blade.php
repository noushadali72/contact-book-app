@extends("layouts.main")

@section("title")
<title>Create Contact</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('styles')
    <style>
        .error{
            color:red;
        }
        sup{
            color:red;
            font-weight: bold;
           
        }
    </style>
@endsection
@section("content")
    <div class="container mt-5">
        <div class="card p-4">
            <h2>Add new Contact</h2>

            <div id="message"></div>
            <form id="form">
                
                <div class="mb-3">
                    <label for="name" class="form-label">Name<sup>*</sup></label>
                    <input id="name" name="name" type="text" class="form-control">
                    <span class="error" id="nameErr"></span>
            
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email<sup>*</sup>:</label>
                    <input type="email" name="email" class="form-control" id="email">
                    <span class="error" id="emailErr"></span>
                   
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone<sup>*</sup>:</label>
                    <input type="text" name="phone" class="form-control" id="phone">
                    <span class="error" id="phoneErr"></span>
                   
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address<sup>*</sup>:</label>
                    <input type="text" name="address" class="form-control" id="address">
                    <span class="error" id="addressErr"></span>
                  
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes<sup>*</sup>:</label>
                    <textarea name="notes" id="notes" cols="30" rows="10" class="form-control"></textarea>
                    <span class="error" id="notesErr"></span>
                   
                </div>

                <div class="mb-3">
                    <label for="group" class="form-label">Group<sup>*</sup>:</label>
                    <select name="group_id" id="group" class="form-control">
                        <option value="" selected></option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                 <span class="error" id="groupErr"></span>
                </div>

                <button class="btn btn-primary" id="submit" type="submit">Submit</button>

            </form>
        </div>
    </div>
    <div id="createToastContainer"></div>
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


            $("#name").on("input blur",validateName);
            $("#email").on("input blur",validateEmail);
            $("#phone").on("input blur",validatePhone);
            $("#notes").on("input blur",validateNotes);
            $("#group").on("input blur",validateGroup);
            $("#address").on("input blur",validateAddress);


            $("#form").on("submit",function(e){
                e.preventDefault();
                isValid = true;
                validateName();
                validateEmail();
                validatePhone();
                validateNotes();
                validateGroup();
                validateAddress();

                if(isValid){
                    let form = document.getElementById("form");
                    let formdata = new FormData(form);
                    $.ajax({
                        url:'{{ route('contacts.store') }}',
                        type:'POST',
                        processData:false,
                        contentType: false,
                        data: formdata,
                        headers: {
                                   
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                        success: function(res){
                           
                                $("#nameErr").text("");
                                $("#emailErr").text("");
                                $("#phoneErr").text("");
                                $("#addressErr").text("");
                                $("#notesErr").text("");
                                $("#groupErr").text("");

                                showCreateToast("success",res.message);
                                $("#form")[0].reset();
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
                                showCreateToast("danger","Unable to Create Contact Please Try again!");
                            }
                         
                        }
                    });
                }
            })

        });


             function showCreateToast(type,message){
        $("#createToastContainer").html(`
        
            <div id="toast" class="toast position-fixed bottom-0 end-0 m-5 p-3 align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body">
    ${message}
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>
        
        `);


        $(`#toast`).toast("show");
    }


      

    </script>
@endsection