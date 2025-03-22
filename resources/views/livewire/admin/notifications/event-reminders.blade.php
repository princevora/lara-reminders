<div class="mt-10 max-w-4xl mx-auto">
    <div class="max-w-sm mx-auto mb-10">
        {{-- <flux:input size="sm"   /> --}}

        <flux:label badge="Required">Message</flux:label>
        <flux:input.group class="my-2 ">
            <flux:input 
                wire:model="message" 
                placeholder="Message"
            />
            <flux:button wire:click="notifyAllAttendees()" icon="bell">Notify All Attendees</flux:button>
        </flux:input.group>
        <flux:text class="mt-2">This message can be used to notify seperate user.</flux:text>
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
                                <flux:icon.information-circle variant="mini"/>  {{ Str::limit($event->title, 50) }}
                            </flux:modal.trigger>
                        </td>
                        <td class="px-6 py-3">
                        <flux:button wire:click="notifyUser('{{ $event->user->id }}')" class="cursor-pointer" icon="bell">Notify</flux:button>
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
