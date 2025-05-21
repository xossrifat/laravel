<div class="message-item {{ $message->is_admin ? 'admin-message' : 'user-message' }}">
    <div class="flex {{ $message->is_admin ? 'justify-start' : 'justify-end' }} items-end gap-2">
        @if($message->is_admin)
            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                SP
            </div>
        @endif
        <div class="max-w-xs sm:max-w-sm md:max-w-md group {{ $message->is_admin ? 'bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700' : 'bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 text-white' }} rounded-t-2xl {{ $message->is_admin ? 'rounded-br-2xl' : 'rounded-bl-2xl' }} p-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="message-content break-words">{{ $message->message }}</div>
            <div class="flex items-center justify-end gap-1 mt-1 opacity-60 group-hover:opacity-100 transition-opacity">
                <span class="text-xs {{ $message->is_admin ? 'text-gray-500 dark:text-gray-400' : 'text-indigo-100 dark:text-indigo-200' }}">
                    {{ $message->created_at->format('h:i A') }}
                </span>
                @if(!$message->is_admin)
                    @if($message->read_at)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $message->is_admin ? 'text-indigo-500' : 'text-indigo-100' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $message->is_admin ? 'text-gray-400' : 'text-indigo-100' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    @endif
                @endif
            </div>
        </div>
        @if(!$message->is_admin)
            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 text-xs font-bold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name ?? 'Y', 0, 1)) }}
            </div>
        @endif
    </div>
</div> 