<?php

declare(strict_types=1);

?>


<x-filament-panels::page>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        @foreach($this->record->expenses as $expense)
            <x-filament::card>
                <p><strong>Data:</strong> {{ $expense->date->format('d/m/y') }}</p>
                <p><strong>Valor:</strong> {{ $expense->amount }}</p>
                <p><strong>Descrição:</strong> {{ $expense->description }}</p>


                @if ($expense->hasMedia('receipt'))
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        @foreach ($expense->getMedia('receipt') as $image)
                            <img
                                src="{{$image->getUrl()}}"
                                alt="Recibo"
                                class="w-full h-32 object-cover rounded shadow"
                            />
                        @endforeach
                    </div>
                @endif
            </x-filament::card>
        @endforeach
    </div>

    <form wire:submit.prevent="save" class="space-y-6 mb-3">
        <section>
            <p>{{ $this->form }}</p>

            <x-filament::button type="submit">
                Submit
            </x-filament::button>
        </section>
    </form>
</x-filament-panels::page>
<?php
