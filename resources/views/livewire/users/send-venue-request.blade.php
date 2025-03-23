<div class="overflow-x-auto shadow-lg rounded-lg">
    
    @if (!is_null($response_message) && !is_null($message_type))
        <div class="my-4">
            <flux:callout variant="{{ $message_type == 0 ? 'danger' : 'success' }}" icon="check-circle" heading="{{ $response_message }}" />
        </div>
    @endif

    <table class="w-full rounded-lg text-gray-200">
        <thead class="bg-gray-900 text-gray-300">
            <tr>
                <th class="px-6 py-3 text-left uppercase text-sm font-semibold">ID</th>
                <th class="px-6 py-3 text-left uppercase text-sm font-semibold">Name</th>
                <th class="px-6 py-3 text-left uppercase text-sm font-semibold">Request</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @foreach ($venues as $venue)
                <tr class="hover:bg-gray-700 transition duration-200">
                    <td class="px-6 py-3">#{{ $loop->iteration }}</td>
                    <td class="px-6 py-3">{{ $venue->name }}</td>
                    <td class="px-6 py-3">
                        <flux:button wire:click="requestVenue('{{ $venue->id }}')" class="cursor-pointer"
                            icon="bell">Request For Venue</flux:button>
                    </td>
                </tr>

                <flux:modal name="edit-profile-{{ $venue->id }}" class="md:w-96">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">Event - {{ $venue->id }}</flux:heading>
                            <flux:subheading>Event details.</flux:subheading>
                        </div>

                        <h1>{{ $venue->title }}</h1>
                        <p>{{ $venue->description }}</p>
                    </div>
                </flux:modal>
            @endforeach
        </tbody>
    </table>
    <script>
        document.addEventListener('clearMessage', () => {
            setTimeout(() => {
                @this.set('response_message', ''); 
                @this.set('message_type', null); 
            }, 1500);
        })
    </script>
</div>