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
             <a href="{{ route('contacts.index') }}"><-back</a>
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
    <div id="updateToastContainer"></div>
@endsection

@section('scripts')
    <script>


function validateForm() {

    let isValid = true;

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^\+?[1-9]\d{1,14}$/;

    const fields = {
        name: {
            value: $("#name").val().trim(),
            required: "Name is Required!"
        },
        email: {
            value: $("#email").val().trim(),
            required: "Email is Required!",
            regex: emailRegex,
            invalid: "Email must be valid!"
        },
        phone: {
            value: $("#phone").val().trim(),
            required: "Phone no is Required!",
            regex: phoneRegex,
            invalid: "Phone no must be valid!"
        },
        notes: {
            value: $("#notes").val().trim(),
            required: "Notes is Required!"
        },
        group: {
            value: $("#group").val().trim(),
            required: "Group is Required!"
        },
        address: {
            value: $("#address").val().trim(),
            required: "Address is Required!"
        }
    };

    let keys = Object.keys(fields);

    keys.forEach(function (key) {
        let field = fields[key];
        let error = "";

        if (field.value === "") {
            error = field.required;
            isValid = false;
        } else if (field.regex && !field.regex.test(field.value)) {
            error = field.invalid;
            isValid = false;
        }

        $("#" + key + "Err").text(error);
    });

    return isValid;
}


$("#phone").on("input", function () {
    let value = $(this).val();

    value = value.replace(/(?!^\+)[^\d]/g, "");

    $(this).val(value);

});

$("#name,#email,#phone,#notes,#group,#address")
    .on("input blur", validateForm);

$("#form").on("submit", function (e) {
    e.preventDefault();

    if (validateForm()) {

                    let form = document.getElementById("form");
                    let formdata = new FormData(form);
                    $.ajax({
                        url:"{{ route('contacts.update',$contact->id) }}",
                        type:'PUT',
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

                                    showUpdateToast("success",res.message);

                                    setTimeout(function(){
                                        window.location.href = "/";
                                    },1000)
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
                                showUpdateToast("danger","Unable to Update Contact Try Again!");
                            }
                         
                        }
                    });
                
   
    }
});
     

function showUpdateToast(type,message){

        $("#updateToastContainer").html(`
        
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