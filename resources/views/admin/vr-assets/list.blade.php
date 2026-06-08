@extends('layouts.dashboard')

@section('title', 'VR Assets Management')
@section('page', 'admin-vr-assets')

@section('content')
<div class="page-header">
  <h1>VR Assets Management</h1>
  <p>Manage therapeutic VR experiences</p>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="toolbar">
  <a href="{{ route('admin.vr-assets.create') }}" class="btn btn-primary">+ Add New VR Asset</a>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>Type</th>
          <th>Duration</th>
          <th>Uses</th>
          <th>Rating</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($assets as $asset)
          <tr>
            <td><strong>{{ $asset->title }}</strong></td>
            <td>{{ $asset->category }}</td>
            <td>{{ ucfirst($asset->file_type) }}</td>
            <td>{{ $asset->duration_minutes }} min</td>
            <td>{{ $asset->usage_count }}</td>
            <td>{{ $asset->average_rating ?? 'N/A' }}/5</td>
            <td>
              @if($asset->is_active)
                <span class="badge badge--success">Active</span>
              @else
                <span class="badge badge--neutral">Inactive</span>
              @endif
            </td>
            <td class="actions-cell">
              <a href="{{ route('admin.vr-assets.edit', $asset) }}" class="btn btn-secondary btn-sm">Edit</a>
              <form method="POST" action="{{ route('admin.vr-assets.destroy', $asset) }}" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="empty-cell">
              No VR assets yet. <a href="{{ route('admin.vr-assets.create') }}">Create the first one</a>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
