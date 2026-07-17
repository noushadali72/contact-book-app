@extends("layouts.main")

@section('title')
    <title>Contacts</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Contacts</h2>
        <a href="/create" class="btn btn-success">Create Contact</a>
    </div>
    <div class="search-bar my-4">
            <div class="d-flex align-items-center gap-2">
                <input name="search" type="text" class="form-control" id="search" placeholder="Type here to search....">
            </div>
    </div>
    <div id="message"></div>
    <div class="d-flex justify-content-end my-4">
        <span>Sort By:</span>
        <select name="sortBy" id="sortBy">
            <option value="name">Name</option>
            <option value="created_at" selected>Date</option>
        </select>
        <select name="direction" id="sortDirection">
                <option value="asc">Ascending</option>
                <option value="desc" selected>Descending</option>
        </select>
    </div>
    <table class="table table-bordered">
        <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Group</th>
                    <th>notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="contacts">

            </tbody>
        </table>


<div id="pagination" class="d-flex justify-content-center mt-4">
    <nav>
        <ul class="pagination pagination-md mb-0" id="pagination-links"></ul>
    </nav>
</div>


    </div>
    <div id="modalContainer"></div>
    <div id="deleteToastContainer"></div>
@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js" integrity="sha512-JZSo0h5TONFYmyLMqp8k4oPhuo6yNk9mHM+FY50aBjpypfofqtEWsAgRDQm94ImLCzSaHeqNvYuD9382CEn2zw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>


   var totalRecords = 0;
    var totalPages = 0;
    var currentPage =1;
    var searchQuery = "";
    var sortBy = "created_at";
    var sortDirection = "desc";

    $(document).ready(function(){
 
    
        $("#search").on('keyup',$.debounce(400,function(e){

                console.log($(this).val())
                if($(this).val().trim()!=""){
                    searchQuery = $(this).val().trim();
                    getPage(1);
                }
        }));
        
        $("#sortBy").on("change",function(e){
            if($("#sortBy").val().trim()==""){
                return;
            }

            sortBy = $("#sortBy").val().trim();
            getPage(1);
        });

        $("#sortDirection").on("change",function(e){
            if($("#sortDirection").val().trim()==""){
                return;
            }

            sortDirection = $("#sortDirection").val().trim();
            getPage();
        })
       getPage(1);

    });

 

    function getPage(pageNo = 1) {

    currentPage = pageNo;

    $("#contacts").empty();

    $.ajax({
        url: "/",
        type: "GET",
        data: {
            page: pageNo,
            searchQuery: searchQuery,
            sortBy: sortBy,
            sortDirection: sortDirection
        },
        success: function (res) {
            if (!res.contacts) return;

            const contacts = res.contacts;

            console.log(res)
            contacts.data.forEach(contact => {

                $("#contacts").append(`
                    <tr>
                        <td>${contact.name}</td>
                        <td>${contact.phone}</td>
                        <td>${contact.email}</td>
                        <td>${contact.address}</td>
                        <td>${contact.group.name}</td>
                        <td>${contact.notes}</td>
                        <td>
                            <a href="/edit/${contact.id}" class="btn btn-warning">Edit</a>
                            <button class="btn btn-danger"
                                onclick="showDeleteModal('${contact.name}', ${contact.id})">
                                Delete
                            </button>
                        </td>
                    </tr>
                `);

            });

            totalRecords = contacts.total;
            totalPages = contacts.last_page;

            handlePagination(totalPages);

        },
        error: function (xhr) {
            console.error(xhr);
        }
    });

}
   
    function deleteContact(id){
        let url = `/${id}`;
        $.ajax({
            url:url,
            type:"DELETE",
            processData: false,
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
            },
            success: function(res){
                if(res.message){
                        showDeleteToast("success",res.message,id);
                        getPage(currentPage);
                    }
                    
                $(`#modal-${id}`).modal("hide");
            },
            error: function(xhr,status, error){
                res = xhr.responseJSON;
                if(xhr.responseJSON && xhr.responseJSON.message){
                    
                        showDeleteToast("danger",res.message,id);
                    }
                    
                 $(`#modal-${id}`).modal("hide");
            }
        })
    }

    function showDeleteModal(name,id){
    
    
        $("#modalContainer").html(`
        <div id="modal-${id}" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Do you want to Delete Contact?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Once you delete action cannot be undone..</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-danger" onclick="deleteContact(${id})">Delete</button>
                                </div>
                                </div>
  </div>
  </div>
  `);

  $(`#modal-${id}`).modal('show');
    }

    function showDeleteToast(type,message,id){
        $("#deleteToastContainer").html(`
        
            <div id="toast-${id}" class="toast position-fixed bottom-0 end-0 m-5 p-3 align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body">
    ${message}
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>
        
        `);


        $(`#toast-${id}`).toast("show");
    }

function handlePagination(totalPages) {

    const paginationLinks = $("#pagination-links");
    paginationLinks.empty();

    if (totalPages <= 1) {
        $("#pagination").hide();
        return;
    }

    $("#pagination").show();

    // Previous
    paginationLinks.append(`
        <li class="page-item ${currentPage == 1 ? 'disabled' : ''}">
            <a href="javascript:void(0)" class="page-link"
                onclick="${currentPage > 1 ? `getPage(${currentPage - 1})` : ''}">
               Previous
            </a>
        </li>
    `);

    let start = Math.max(1, currentPage - 2);
    let end = Math.min(totalPages, currentPage + 2);

    if (start > 1) {
        paginationLinks.append(`
            <li class="page-item">
                <a href="javascript:void(0)" class="page-link"
                    onclick="getPage(1)">1</a>
            </li>
        `);

        if (start > 2) {
            paginationLinks.append(`
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `);
        }
    }

    for (let i = start; i <= end; i++) {
        paginationLinks.append(`
            <li class="page-item ${currentPage === i ? 'active' : ''}">
                <a href="javascript:void(0)"
                   class="page-link"
                   onclick="getPage(${i})">
                    ${i}
                </a>
            </li>
        `);
    }

    if (end < totalPages) {

        if (end < totalPages - 1) {
            paginationLinks.append(`
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `);
        }

        paginationLinks.append(`
            <li class="page-item">
                <a href="javascript:void(0)"
                   class="page-link"
                   onclick="getPage(${totalPages})">
                    ${totalPages}
                </a>
            </li>
        `);
    }


    paginationLinks.append(`
        <li class="page-item ${currentPage == totalPages ? 'disabled' : ''}">
            <a href="javascript:void(0)"
               class="page-link"
               onclick="${currentPage < totalPages ? `getPage(${currentPage + 1})` : ''}">
               Next
            </a>
        </li>
    `);
}

</script>
    
@endsection

