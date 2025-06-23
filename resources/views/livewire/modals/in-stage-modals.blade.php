@props(['list', 'stage', 'sub'])
<?php
use function Livewire\Volt\{state, computed};
use Illuminate\Support\Number;

state(['list', 'stage', 'sub']);
?>

<div>
  <x-modal name="in-stage-modal-{{ $stage }}-{{ $sub }}">
    <div class="p-6 bg-white rounded-lg max-w-lg mx-auto text-gray-800">
      <h2 class="text-lg font-bold text-purple-700 mb-4">سوالات موجود در خانه
        {{ Number::format($stage) }}
        و روز
        {{ Number::format($this->sub) }}</h2>
      @if (count($list) != 0)
        <p class="leading-relaxed text-justify text-red-600 font-bold">
          این ها را بخاطر نسپارید، ممکن است فرایند یادگیری را مختل کند!
        </p>
      @endif
      <div class="overflow-x-auto rounded-lg shadow my-2">
        <table class="min-w-full divide-y divide-gray-200 text-center">
          <thead class="bg-gray-50 border-b-4 border-blue-300">
            <tr>
              <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">سوال</th>
              <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">جواب</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($list as $item)
              <tr class="hover:bg-blue-50 transition duration-150 ease-in-out">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ $item->courseQuestion->question->question }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $item->courseQuestion->question->answer }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if (count($list) == 0)
        <p class="text-yellow-500 italic font-bold text-center">{{ __('messages.empty') }}</p>
      @endif
      <div class="mt-6 flex justify-end">
        {{-- <x-danger-button type="button"
          wire:click="dispatch('close-modal', 'in-stage-modal-{{ $stage }}-{{ $sub }})">{{ __('messages.close') }}</x-danger-button> --}}
      </div>
    </div>
  </x-modal>
</div>
