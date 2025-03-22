<div class="mt-10 max-w-4xl mx-auto">
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
                            <span wire:click="notifyUser('{{ $event->user->id }}')" class="cursor-pointer px-3 py-1 text-xs font-semibold text-white rounded-full bg-green-500">
                                Notify
                            </span>
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
