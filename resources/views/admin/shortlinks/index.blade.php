@extends('admin.layouts.app')

@section('title', 'Shortlinks Management')
@section('header', 'Shortlinks Management')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Manage Shortlinks</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.shortlinks.analytics') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-chart-line mr-2"></i> Analytics
            </a>
            <a href="{{ route('admin.shortlinks.config') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-cog mr-2"></i> Configure
            </a>
            <a href="{{ route('admin.shortlinks.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Add New
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Title
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        URL
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Coins
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Verification
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Created
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($shortlinks as $shortlink)
                    <tr>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $shortlink->id }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $shortlink->title }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 max-w-xs truncate">
                            <a href="{{ $shortlink->url }}" target="_blank" class="text-blue-600 hover:text-blue-900 underline">
                                {{ $shortlink->url }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $shortlink->coins }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $shortlink->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $shortlink->active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            @if($shortlink->requires_verification)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Required
                                </span>
                                @if($shortlink->verification_code)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Code: <span class="font-mono font-bold">{{ $shortlink->verification_code }}</span>
                                    </div>
                                @endif
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    None
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            {{ $shortlink->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm font-medium flex space-x-2">
                            <a href="{{ route('admin.shortlinks.edit', $shortlink->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            
                            <form action="{{ route('admin.shortlinks.toggle', $shortlink->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="{{ $shortlink->active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }}">
                                    <i class="fas {{ $shortlink->active ? 'fa-ban' : 'fa-check-circle' }}"></i> 
                                    {{ $shortlink->active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.shortlinks.delete', $shortlink->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this shortlink?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center text-gray-500">
                            No shortlinks found. <a href="{{ route('admin.shortlinks.create') }}" class="text-blue-600">Create one</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4">
        {{ $shortlinks->links() }}
    </div>
</div>
@endsection 