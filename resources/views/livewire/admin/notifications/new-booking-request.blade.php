<div class="container mx-auto p-6">
    @if (!is_null($response_message) && !is_null($message_type))
        <div class="my-4">
            <flux:callout variant="{{ $message_type == 0 ? 'danger' : 'success' }}" icon="check-circle"
                heading="{{ $response_message }}" />
        </div>
    @endif
    @if (!is_null($user) || !is_null($venue))
        <div class="flex justify-center items-center p-6 ">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ $venue?->name ?? 'Not selected yet' }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Owned by: <span class="font-medium">
                        {{ $venue?->owner?->name ?? 'Not selected yet' }}
                    </span>
                </p>
                <p class="text-gray-500 dark:text-gray-300 text-sm mt-2">
                    Send Request As : {{ $user?->name ?? 'Not Selected' }}
                </p>
            </div>
        </div>
    @endif

    @if (!is_null($user) && !is_null($venue))
        <div class="flex justify-center mb-4 mt-1">
            <flux:button wire:click="sendVenueRequest">Send Request</flux:button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- First Table -->
        <div class="overflow-hidden rounded-lg shadow-lg">
            <table class="min-w-full border border-gray-300 dark:border-gray-600 rounded-lg ">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">ID</th>
                        <th class="px-6 py-3 text-left font-semibold">Venue</th>
                        <th class="px-6 py-3 text-left font-semibold">Send Req. For</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300">
                    @foreach ($venues as $venue)
                        <tr
                            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-3">{{ $loop->iteration }}</td>
                            <td class="px-6 py-3">{{ Str::limit($venue->name, 40) }}</td>
                            <td class="px-6 py-3">
                                <flux:button class="cursor-pointer" wire:click="selectVenue('{{ $venue->id }}')">
                                    Select For</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Second Table -->
        <div class="overflow-hidden rounded-lg shadow-lg">
            <table class="min-w-full border border-gray-300 dark:border-gray-600 rounded-lg h-auto">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">ID</th>
                        <th class="px-6 py-3 text-left font-semibold">User</th>
                        <th class="px-6 py-3 text-left font-semibold">Send Request As</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300">
                    @foreach ($users as $user)
                        <tr
                            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-3">{{ $loop->iteration }}</td>
                            <td class="px-6 py-3">{{ $user->name }}</td>
                            <td class="px-6 py-3">
                                <flux:button class="cursor-pointer" wire:click="selectUser('{{ $user->id }}')">
                                    Select For</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
