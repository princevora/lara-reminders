<div>
    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Notifications')" class="grid relative">
            <flux:navlist.item icon="bell-alert" :href="route('user.notifications')" wire:navigate class="relative flex items-center">
                
                <!-- Live Red Dot Indicator -->
                <span class="absolute -top-1 left-5 w-2.5 h-2.5 bg-red-500 rounded-full animate-ping"></span>
                <span class="absolute -top-1 left-5 w-2.5 h-2.5 bg-red-500 rounded-full"></span>

                {{ __('Notifications') }}
                <flux:badge color="lime" class="mx-10" size="sm">{{ $unreadNotifications > 99 ? "99+" : $unreadNotifications }}</flux:badge>
            </flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>
</div>
