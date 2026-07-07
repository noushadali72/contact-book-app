@extends("layouts.main")

@section('title')
    <title>Contacts</title>
@endsection

@section('content')

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Contacts</h2>
        <a href="/create" class="btn btn-success">Create Contact</a>
    </div>
    <div class="search-bar my-4">
        <form>

            <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control" id="search" placeholder="Type here to search....">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
    <table class="table table-bordered">
        <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @if ($contacts &&count($contacts)>0)
                    
                @foreach ($contacts as $contact)
                <tr>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->phone }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->address }}</td>
                    <td>{{ $contact->notes }}</td>
                    <td>
                        <a href="/edit/{{ $contact->id }}" class="btn btn-warning">Edit</a>
                        <a href="/delete/{{ $contact->id }}" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                @endforeach

                @endif
            </tbody>
        </table>
    </div>
@endsection