@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Domains</h1>
        <a href="{{ route('domains.create') }}" class="btn btn-primary">Add Domain</a>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Domain Name</th>
                <th>Register</th>
                <th>NS1</th>
                <th>NS2</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($domains as $domain)
                <tr>
                    <td>{{ $domain->name }}</td>
                    <td>{{ $domain->domain_name }}</td>
                    <td>{{ $domain->register }}</td>
                    <td>{{ $domain->ns1 }}</td>
                    <td>{{ $domain->ns2 }}</td>
                    <td>
                        <a href="{{ route('domains.edit', $domain->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('domains.destroy', $domain->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
