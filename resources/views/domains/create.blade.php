@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Domain</h1>
        <form method="POST" action="{{ route('domains.store') }}">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="domain_name">Domain Name</label>
                <input type="text" class="form-control" id="domain_name" name="domain_name" required>
            </div>
            <div class="form-group">
                <label for="register">Register</label>
                <select class="form-control" id="register" name="register" required>
                    <option value="GoDaddy">GoDaddy</option>
                    <option value="NameCheap">NameCheap</option>
                </select>
            </div>
            <div class="form-group">
                <label for="ns1">NS1</label>
                <input type="text" class="form-control" id="ns1" name="ns1" required>
            </div>
            <div class="form-group">
                <label for="ns2">NS2</label>
                <input type="text" class="form-control" id="ns2" name="ns2" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Domain</button>
        </form>
    </div>
@endsection
