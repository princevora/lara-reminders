<div class="flex flex-col items-start">
    @include('partials.settings-heading')

    <div class="mx-auto">
        <flux:radio.group x-data variant="segmented" x-model="$wire.selected">
            <flux:radio wire:click="$set('selected', 1)" value="1" icon="calendar-days">{{ __('Event Reminders') }}</flux:radio>
            <flux:radio wire:click="$set('selected', 2)" value="2" icon="plus">{{ __('New Venue Booking Request') }}</flux:radio>
            <flux:radio wire:click="$set('selected', 3)" value="3" icon="computer-desktop">{{ __('System Notifications') }}</flux:radio>
        </flux:radio.group>
    </div>

    @if ($selected == 1)
        {!! Livewire\Livewire::mount($components[$selected]) !!}
    @endif
</div>
