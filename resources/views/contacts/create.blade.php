@extends("layouts.main")

@section("title")
<title>Create Contact</title>
@endsection


@section("content")
    <div class="container mt-5">
        <div class="card p-4">
            <h2>Add new Contact</h2>
            <form id="form" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input id="name" type="text" class="form-control">
                    <span class="error" id="nameErr"></span>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email">
                    <span class="error" id="emailErr"></span>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="text" class="form-control" id="phone">
                    <span class="error" id="phoneErr"></span>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" class="form-control" id="address">
                    <span class="error" id="addressErr"></span>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes:</label>
                    <textarea name="notes" id="notes" cols="30" rows="10" class="form-control"></textarea>
                    <span class="error" id="notesErr"></span>
                </div>

                <div class="mb-3">
                    <label for="group" class="form-label">Group:</label>
                    <select name="group" id="group" class="form-control">
                        <option value="1">Group 1</option>
                    </select>
                </div>

                <button class="btn btn-primary" id="submit">Submit</button>

            </form>
        </div>
    </div>
@endsection