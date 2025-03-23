<div wire:poll.60s class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-5 space-y-5">
    <div class="flex justify-between">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-200">Notifications</h2>
        @if ($unreadRequests > 0)
            <flux:tooltip content="Mark All as read">
                <flux:button class="cursor-pointer" wire:click="markAllAsRead" icon="envelope-open" variant="ghost"></flux:button>
            </flux:tooltip>
        @endif
    </div>

    @foreach ($requests as $request)
        <div x-data="{ expanded: false, dismissed: false }" x-show="!dismissed"
            class="select-none cursor-pointer relative border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow-sm transition-all duration-200">

            <div class="flex items-center space-x-3 relative">
                <!-- Icon -->
                <div class="p-2 bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-400 rounded-full">
                    <flux:icon name="plus" class="w-5 h-5"></flux:icon>
                </div>

                <!-- Title + Mark as Read Button -->
                <div class="flex-1 flex items-center justify-between">
                    <h3 @click="expanded = !expanded"
                        class="text-sm font-medium text-gray-900 dark:text-gray-100 cursor-pointer">
                        New Venue Request
                    </h3>

                    <!-- Mark as Read Button (Only for Unread Notifications) -->
                    @if (is_null($request->read_at))
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            <flux:tooltip content="Mark As Read">
                                <flux:button wire:click="markAsRead('{{ $request->id }}')"
                                    class="cursor-pointer text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100"
                                    icon="eye" size="xs" variant="ghost">
                                </flux:button>
                            </flux:tooltip>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Notification Content -->
            <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                <p @click="expanded = !expanded" class="cursor-pointer">
                    @php
                        
                    @endphp
                    <span x-show="!expanded">{{ Str::limit($request->message, 15) }}...</span>
                    <span x-show="expanded" x-transition.opacity>{{ $request->message }}</span>
                </p>
            </div>

            <!-- Read More / Collapse Button -->
            <div class="mt-2 flex justify-between items-center">
                <button @click="expanded = !expanded"
                    class="text-blue-500 dark:text-blue-400 font-semibold hover:underline text-xs transition">
                    <span x-show="!expanded">Read more</span>
                    <span x-show="expanded">Collapse</span>
                </button>

                <span class="text-xs text-gray-500 dark:text-gray-400">
                    @if ($request->created_at->diffInMinutes(now()) < 1)
                        Just Now
                    @else
                        {{ $request->created_at->diffForHumans() }}
                    @endif
                </span>
            </div>
        </div>
    @endforeach
</div>
