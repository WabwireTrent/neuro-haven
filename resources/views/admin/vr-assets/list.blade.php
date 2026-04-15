@extends('layouts.dashboard')

@section('title', 'VR Assets Management')
@section('page', 'admin-vr-assets')

@section('content')
<header class="dashboard-header">
  <h1>VR Assets Management</h1>
  <p class="dashboard-streak">Manage therapeutic VR experiences</p>
</header>

@if(session('success'))
  <div class="alert alert-success" role="alert">
    {{ session('success') }}
  </div>
@endif

<section class="dashboard-main">
  <div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.vr-assets.create') }}" class="btn btn-primary">+ Add New VR Asset</a>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="border-bottom: 2px solid #e3e3e0;">
            <th style="padding: 1rem; text-align: left; font-weight: 600;">Title</th>
            <th style="padding: 1rem; text-align: left; font-weight: 600;">Category</th>
            <th style="padding: 1rem; text-align: left; font-weight: 600;">Type</th>
            <th style="padding: 1rem; text-align: left; font-weight: 600;">Duration</th>
            <th style="padding: 1rem; text-align: left; font-weight: 600;">Uses</th>
            <th style="padding: 1rem; text-align: left; font-weight: 600;">Rating</th>
            <th style="padding: 1rem; text-align: left; font-weight: 600;">Status</th>
            <th style="padding: 1rem; text-align: left; font-weight: 600;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($assets as $asset)
            <tr style="border-bottom: 1px solid #e3e3e0;">
              <td style="padding: 1rem;">
                <strong>{{ $asset->title }}</strong>
              </td>
              <td style="padding: 1rem;">{{ $asset->category }}</td>
              <td style="padding: 1rem;">{{ ucfirst($asset->file_type) }}</td>
              <td style="padding: 1rem;">{{ $asset->duration_minutes }} min</td>
              <td style="padding: 1rem;">{{ $asset->usage_count }}</td>
              <td style="padding: 1rem;">{{ $asset->average_rating ?? 'N/A' }}/5</td>
              <td style="padding: 1rem;">
                @if($asset->is_active)
                  <span class="badge" style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">Active</span>
                @else
                  <span class="badge" style="background: #6b7280; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">Inactive</span>
                @endif
              </td>
              <td style="padding: 1rem;">
                <a href="{{ route('admin.vr-assets.edit', $asset) }}" class="btn btn-secondary" style="font-size: 0.875rem; padding: 0.5rem 1rem;">Edit</a>
                <form method="POST" action="{{ route('admin.vr-assets.destroy', $asset) }}" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger" style="font-size: 0.875rem; padding: 0.5rem 1rem; background: #ef4444;">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="padding: 2rem; text-align: center; color: #706f6c;">
                No VR assets yet. <a href="{{ route('admin.vr-assets.create') }}">Create the first one</a>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
