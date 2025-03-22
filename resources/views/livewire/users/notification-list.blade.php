<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-4 space-y-4">
    <h2 class="text-lg font-semibold text-gray-800">Notifications</h2>
    
    @foreach($notifications as $notification)
        <flux:callout icon="{{ $notification['icon'] }}" class="border border-gray-200 bg-gray-50 p-3 rounded-lg shadow-sm">
            <flux:callout.heading class="text-sm font-medium text-gray-900">
                {{ $notification['title'] }}
            </flux:callout.heading>

            <flux:callout.text class="text-gray-700 text-sm">
                {{ $notification['message'] }}
                <flux:callout.link href="{{ $notification['link'] }}" class="text-blue-500 font-semibold hover:underline">
                    Read more
                </flux:callout.link>
            </flux:callout.text>

            <span class="text-xs text-gray-500">{{ $notification['time'] }}</span>
        </flux:callout>
    @endforeach
</div>
