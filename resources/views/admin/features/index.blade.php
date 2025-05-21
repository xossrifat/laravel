@extends('admin.layouts.app')

@section('title', 'ফিচার ফ্ল্যাগ পরিচালনা')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">ফিচার ফ্ল্যাগ তালিকা</h3>
                    <a href="{{ route('admin.features.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> নতুন ফিচার ফ্ল্যাগ
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>আইডি</th>
                                    <th>কী</th>
                                    <th>নাম</th>
                                    <th>বিবরণ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>তৈরির তারিখ</th>
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($features as $feature)
                                    <tr>
                                        <td>{{ $feature->id }}</td>
                                        <td><code>{{ $feature->key }}</code></td>
                                        <td>{{ $feature->name }}</td>
                                        <td>{{ $feature->description }}</td>
                                        <td>
                                            <span class="badge bg-{{ $feature->is_enabled ? 'success' : 'danger' }}">
                                                {{ $feature->is_enabled ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                            </span>
                                        </td>
                                        <td>{{ $feature->created_at->format('d M Y, h:i A') }}</td>
                                        <td class="d-flex">
                                            <form action="{{ route('admin.features.toggle', $feature->id) }}" method="POST" class="me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-{{ $feature->is_enabled ? 'warning' : 'success' }} btn-sm">
                                                    <i class="fas fa-toggle-{{ $feature->is_enabled ? 'off' : 'on' }}"></i> 
                                                    {{ $feature->is_enabled ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.features.edit', $feature->id) }}" class="btn btn-info btn-sm me-2">
                                                <i class="fas fa-edit"></i> সম্পাদনা
                                            </a>
                                            <form action="{{ route('admin.features.delete', $feature->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে আপনি এই ফিচার ফ্ল্যাগটি মুছতে চান?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> মুছুন
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">কোনো ফিচার ফ্ল্যাগ পাওয়া যায়নি!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 