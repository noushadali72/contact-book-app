@extends("layouts.main")

@section('title')
    <title>Contacts</title>
@endsection

@section('content')

<div class="container mt-5">
    <h2>Contacts</h2>
    <table class="table table-bordered">
        <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>notes</th>
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
                </tr>
                @endforeach

                @endif
            </tbody>
        </table>
    </div>
@endsection