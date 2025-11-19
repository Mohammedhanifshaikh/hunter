@extends('layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Attach Plan to Company</h5>
        </div>
        <div class="card-body">
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('attach.plan.store') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label">Company</label>
              <select name="company_id" class="form-select" required>
                <option value="">Select company</option>
                @foreach($companies as $c)
                  <option value="{{ $c->id }}" {{ (old('company_id', request('company_id')) == $c->id) ? 'selected' : '' }}>
                    {{ $c->company_name }} ({{ $c->email }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Plan</label>
              <select name="subscription_id" class="form-select" required>
                <option value="">Select plan</option>
                @foreach($plans as $p)
                  <option value="{{ $p->id }}" {{ old('subscription_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->name }} - {{ $p->duration }} mo - ₹{{ number_format($p->price, 2) }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Start Date (optional)</label>
              <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
              <div class="form-text">Defaults to today if left empty. End date auto-calculated from plan duration.</div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Attach Plan</button>
              <a href="{{ route('company.list') }}" class="btn btn-secondary">Back</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
