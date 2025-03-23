<div class="mt-10 max-w-4xl mx-auto">
    <div class="max-w-sm mx-auto mb-10">
        <flux:label badge="Notification Channel"></flux:label>
        <div class="flex gap-4 *:gap-x-2 mt-5">
            <flux:checkbox wire:model="notification_channels.web_sockets" value="english" label="WebSockets" />
            <flux:checkbox wire:model="notification_channels.email" value="spanish" label="Email" />
            <flux:checkbox wire:model="notification_channels.push" value="german" label="Push Notifications" />
        </div>
        <div class="mt-5">
            <flux:input wire:model="message" label="message" />
        </div>
        <flux:input.group class="my-4">
            <flux:button class="mx-auto cursor-pointer" wire:click="notifyUsers()" icon="bell">Notify Users About
                System Modification
            </flux:button>
        </flux:input.group>
    </div>
</div>
