<div class="mt-10 max-w-4xl mx-auto">
    <div class="max-w-sm mx-auto mb-10">
        <flux:label  badge="Notification Channel"></flux:label>
        <div class="flex gap-4 *:gap-x-2 mt-5">
            <flux:checkbox wire:model="notification_channels.web_sockets" 
                value="english" 
                label="WebSockets" 
            />
            <flux:checkbox wire:model="notification_channels.email" 
                value="spanish" 
                label="Email" 
            />
            {{-- <flux:checkbox wire:model="notification_channels.push" 
                value="german" 
                label="Push Notifications" 
            /> --}}
        </div>
        <flux:input.group class="my-4">
            <flux:button class="mx-auto cursor-pointer" wire:click="notifyAll()" icon="bell">Notify All Attendees
            </flux:button>
        </flux:input.group>
    </div>

    <div class="overflow-x-auto shadow-lg rounded-lg">
        <table class="w-full rounded-lg text-gray-200">
            <thead class="bg-gray-900 text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left uppercase text-sm font-semibold">ID</th>
                    <th class="px-6 py-3 text-left uppercase text-sm font-semibold">Name</th>
                    <th class="px-6 py-3 text-left uppercase text-sm font-semibold">Event</th>
                    <th class="px-6 py-3 text-left uppercase text-sm font-semibold">Notify</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach ($events as $event)
                    <tr class="hover:bg-gray-700 transition duration-200">
                        <td class="px-6 py-3">#{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">{{ $event->user->name }}</td>
                        <td class="px-6 py-3 flex gap-2 inline-flex cursor-pointer">
                            <flux:modal.trigger name="edit-profile-{{ $event->id }}">
                                <flux:icon.information-circle variant="mini" /> {{ Str::limit($event->title, 50) }}
                            </flux:modal.trigger>
                        </td>
                        <td class="px-6 py-3">
                            <flux:button wire:click="notifyUser('{{ $event->user->id }}', '{{ $event->id }}')" class="cursor-pointer"
                                icon="bell">Notify</flux:button>
                            {{-- <span wire:click="notifyUser('{{ $event->user->id }}')" class="">
                            </span> --}}
                        </td>
                    </tr>

                    <flux:modal name="edit-profile-{{ $event->id }}" class="md:w-96">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">Event - {{ $event->id }}</flux:heading>
                                <flux:subheading>Event details.</flux:subheading>
                            </div>

                            <h1>{{ $event->title }}</h1>
                            <p>{{ $event->description }}</p>
                        </div>
                    </flux:modal>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $events->links('pagination::tailwind') }}
    </div>
</div>
