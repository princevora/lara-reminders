<div>
    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Notifications')" class="grid">
            <flux:navlist.item icon="bell-alert" :href="url()->to('/')"
                 wire:navigate>
                 {{ __('Notifications') }}
                 <flux:badge color="lime" class="mx-10" size="sm">0</flux:badge>
            </flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>
</div>
