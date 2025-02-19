@props(['id'])

<div x-data="{
    fileName: '',
    isSelected: false,
    isDragging: false,
    handleInput(event) {
        this.isSelected = true;
        this.fileName = event.target.files[0].name;
        @this.upload('{{ $attributes->wire('model')->value() }}', event.target.files[0]);
    },
    handleDrop(event) {
        this.isSelected = true;
        this.fileName = event.dataTransfer.files[0].name;
        @this.upload('{{ $attributes->wire('model')->value() }}', event.dataTransfer.files[0]);
    },
    removeFile() {
        this.isSelected = false;
        this.fileName = '';
        this.$refs.input.value = '';
        @this.set('{{ $attributes->wire('model')->value() }}', null);
    },
    init() {
        this.$watch('$wire.{{ $attributes->wire('model')->value() }}', value => {
            if (!value) {
                this.removeFile();
            }
        });
    }
}" class="relative flex items-center justify-center w-full py-6">
    
    <label for="{{ $id }}" 
           x-show="!isSelected"
           @dragover.prevent="isDragging = true"
           @dragleave.prevent="isDragging = false"
           @drop.prevent="isDragging = false; handleDrop($event)"
           class="flex flex-col items-center justify-center w-full h-64 border-2 border-purple-500 border-dashed rounded-lg cursor-pointer bg-purple-50 dark:hover:bg-purple-800 dark:bg-purple-700 hover:bg-purple-100 dark:border-purple-600 dark:hover:border-purple-500 dark:hover:bg-purple-600 shadow-lg transform transition-transform duration-300 hover:scale-105 dark:hover:scale-105"
           :class="{'border-purple-700 bg-purple-100 dark:bg-purple-600': isDragging}">
        
        <div class="flex flex-col items-center justify-center p-6">
            <div class="absolute top-2 left-2 w-[max(250px,25%)]">{{ $slot }}</div>
            <svg class="w-12 h-12 mb-4 text-purple-500 dark:text-purple-400 animate-bounce" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
            </svg>
            <p class="mb-2 text-sm text-purple-500 dark:text-purple-400"><span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}</p>
            <p class="text-xs text-purple-500 dark:text-purple-400">
                @if(!empty($file_types) && $file_types->hasActualContent())
                    {{ $file_types }}
                @else
                    {{ __('SVG, PNG, JPG or other image types') }}
                @endif
            </p>
        </div>
        <input @change="handleInput($event)" x-ref="input" {{ $attributes->merge(['id' => $id, 'type' => 'file', 'class' => 'hidden', 'accept'=>'.jpg, .jpeg, .png']) }} />
    </label>

    <div x-show="isSelected" class="flex flex-col items-center justify-center w-full h-64 border-2 border-purple-500 border-dashed rounded-lg bg-purple-50 dark:bg-purple-700 shadow-lg transform transition-transform duration-300 scale-105 dark:scale-105">
        <div class="flex flex-col items-center justify-center p-6">
            <div class="absolute top-2 left-2 w-[max(250px,25%)]">{{ $slot }}</div>
            <svg class="w-12 h-12 mb-4 text-purple-500 dark:text-purple-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
            </svg>
            <p class="mb-2 text-sm text-purple-500 dark:text-purple-400"><span class="font-semibold">{{ __('Selected File:') }}</span> <span x-text="fileName"></span></p>
            <button @click="removeFile" type="button" class="mt-4 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 dark:bg-red-400 dark:hover:bg-red-500">{{ __('Remove File') }}</button>
        </div>
    </div>
</div>