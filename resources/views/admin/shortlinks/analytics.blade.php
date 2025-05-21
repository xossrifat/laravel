@extends('admin.layouts.app')

@section('title', 'Shortlinks Analytics')
@section('header', 'Shortlinks Analytics')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Total Shortlinks</h3>
        <p class="text-3xl font-bold text-blue-600">{{ $shortlinks->count() }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Total Claims</h3>
        <p class="text-3xl font-bold text-green-600">{{ $totalClaims }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Average Claims/Shortlink</h3>
        <p class="text-3xl font-bold text-purple-600">
            {{ $shortlinks->count() > 0 ? round($totalClaims / $shortlinks->count(), 1) : 0 }}
        </p>
    </div>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Shortlinks Performance</h2>
        <a href="{{ route('admin.shortlinks.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Coins</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Total Claims</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Total Coins Given</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shortlinks as $shortlink)
                    <tr>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <div class="text-sm font-medium text-gray-900">{{ $shortlink->title }}</div>
                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ $shortlink->url }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $shortlink->coins }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $shortlink->users_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <span class="text-sm font-medium text-green-600">
                                {{ number_format($shortlink->coins * $shortlink->users_count) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $shortlink->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $shortlink->active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm font-medium">
                            <a href="{{ route('admin.shortlinks.edit', $shortlink->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 