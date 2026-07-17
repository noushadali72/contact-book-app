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


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>


@endsection
@section("content")
    <div class="container mt-5">
        <div class="card p-4">
            <a href="{{ route('contacts.index') }}"><-back</a>
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
                    <select name="group_id" id="group" class="form-control group">
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js">
</script>


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
                                showCreateToast("danger","Unable to Create Contact Try Again!");
                            }
                         
                        }
                    });
                
   
    }
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


$(".group").select2({
    placeholder: "Search Group to Select",
    minimumInputLength: 2,
    ajax: {
        url: "{{ route('searchGroup') }}",
        type: "get",
        delay: 400,
        data: function(params){
            let data = {
                searchGroup: params.term
            }
            return data;
        },
       processResults: function(data){
        let groups = data.groups;
            return {
                results: groups.map(function(item){
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            }
       },
        
        error: function(xhr, status, error){
            console.log(xhr)
        }
    }
  
});

    </script>
@endsection