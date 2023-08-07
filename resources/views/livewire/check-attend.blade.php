<div>
    <form wire:submit.prevent="checkAttendance">
        <label for="verificationCode">Verification Code:</label>
        <input type="text" id="verificationCode" wire:model="verificationCode">
        @error('verificationCode') <span>{{ $message }}</span> @enderror
        @if(session()->has('success'))
        {{ session('success') }}
        @elseif(session()->has('error'))
        {{ session('error') }}
        @endif
        <br>
        <x-filament::button type="submit">提交</x-filament::button>
    </form>
</div>
