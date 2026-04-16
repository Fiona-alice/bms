@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h5 class="mb-4">Edit Client</h5>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('clients.update', $client->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name"
                               class="form-control"
                               value="{{ old('name', $client->name) }}" required>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone"
                               class="form-control"
                               value="{{ old('phone', $client->phone) }}">
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               class="form-control"
                               value="{{ old('email', $client->email) }}">
                    </div>

                    {{-- Address --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address"
                               class="form-control"
                               value="{{ old('address', $client->address) }}">
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="mt-3">
                    <button type="submit" class="btn btn-sm custom-pill-btn">
                        Save
                    </button>

                    <a href="{{ route('clients.index') }}"
                      class="btn btn-sm custom-pill-btn">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection