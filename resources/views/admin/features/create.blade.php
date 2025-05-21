@extends('admin.layouts.app')

@section('title', 'নতুন ফিচার ফ্ল্যাগ তৈরি')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">নতুন ফিচার ফ্ল্যাগ তৈরি</h3>
                    <a href="{{ route('admin.features.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.features.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="key">ফিচার কী <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="key" name="key" value="{{ old('key') }}" required placeholder="উদাহরণ: subscription_feature, nft_marketplace">
                            <small class="text-muted">এটি অবশ্যই অনন্য হতে হবে এবং শুধুমাত্র ছোট অক্ষর, সংখ্যা এবং আন্ডারস্কোর ধারণ করতে পারে।</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="name">ফিচার নাম <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required placeholder="উদাহরণ: সাবস্ক্রিপশন ফিচার">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="description">ফিচার বিবরণ</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="এই ফিচারের বিবরণ লিখুন">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_enabled" name="is_enabled" value="1" {{ old('is_enabled') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_enabled">ফিচার সক্রিয় করুন</label>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> সংরক্ষণ করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 