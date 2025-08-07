@props(['text' => ''])

<?php

use function Livewire\Volt\{state, mount};

state(['text' => '', 'lang' => null]);

$detectLanguage = function () {
    if ($this->lang == null) {
        $result = langof($this->text); // e.g. ["fa" => 0.84]

        $key = array_keys($result)[0];
        $lang = ['fa' => 'fa-IR', 'en' => 'en-US', 'ar' => 'ar-SA'][$key];

        $this->lang = ['lang' => $lang, 'confidence' => $result[$key], $lang => $result[$key]];
    }

    return $this->lang;
};
?>

<div x-data="{
    text: @entangle('text').live,
    lang: null,
    confidence: null,
    speaking: false,
    speak() {
        if (this.text && this.lang) {
            const utterance = new SpeechSynthesisUtterance(this.text);
            utterance.lang = this.lang;
            this.speaking = true;
            utterance.onend = () => this.speaking = false;
            speechSynthesis.speak(utterance);
        }
    }
}" x-init="$wire.detectLanguage().then(result => {
    lang = result['lang'];
    confidence = result['confidence'];
});"
  class="p-4 rounded-2xl shadow-xl bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 max-w-lg mx-auto space-y-4">
  <div class="flex items-center justify-between gap-2">
    <h2 class="text-lg font-semibold">{{ __('tts.detected') }}</h2>
    <button @click="speak" :class="speaking ? 'animate-pulse text-blue-600' : 'text-gray-500 hover:text-blue-600'"
      class="transition" title="Speak text">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M11 5L6 9H2v6h4l5 4V5zm10 7a4 4 0 00-3-3.87m3 3.87a4 4 0 01-3 3.87m0-7.74v7.74" />
      </svg>
    </button>
  </div>

  <template x-if="lang">
    <div class="space-y-1">
      <p class="text-sm text-gray-500">{{ __('tts.lang_code') }}: <strong x-text="lang"></strong></p>
      <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
          :style="'width: ' + (confidence * 100).toFixed(1) + '%'"></div>
      </div>
      <p class="text-xs text-gray-400" x-text="'{{ __('tts.confidence') }}: ' + (confidence * 100).toFixed(1) + '%'">
      </p>
    </div>
  </template>

  <p class="text-sm mt-4 font-mono bg-gray-100 dark:bg-gray-800 p-3 rounded text-gray-700 dark:text-gray-300 whitespace-pre-wrap"
    x-text="text" dir="auto"></p>
</div>
