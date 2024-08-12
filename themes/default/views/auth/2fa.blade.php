<form
    class="flex flex-col gap-2"
    wire:submit="verify">
    <div class="flex flex-col items-center mt-4 mb-10">
        <x-logo />
        <h1 class="text-2xl text-center text-white mt-2">{{ __('auth.verify_2fa') }} </h1>
    </div>
    <x-form.input name="code" type="text" :label="__('account.input.two_factor_code')" :placeholder="__('account.input.two_factor_code_placeholder')" wire:model="code" required noDirty />

    <x-button.primary class="w-full" type="submit">{{ __('auth.verify') }}</x-button.primary>
</form>