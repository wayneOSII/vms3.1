<div>
    <x-filament::button wire:click="generateCode">Generate</x-filament::button>
    
    <x-filament::button wire:click="showCode">showCode</x-filament::button>

    <br>
    <br>
    @if($showcode)
    Generated Verification Code: {{ $showcode }}
    @else
    None
    @endif
</div>
