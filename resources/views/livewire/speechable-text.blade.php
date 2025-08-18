@props(['text' => ''])

<?php

use function Livewire\Volt\{state};

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

<span x-data="{
    text: $wire.entangle('text').live,
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
});" class="flex justify-between items-center gap-1 w-4/5 mx-auto flex-wrap">
  <button @click="speak" :class="speaking ? 'animate-pulse text-blue-600' : 'text-gray-500 hover:text-blue-600'"
    class="transition" :title="(confidence * 100).toFixed(1) + '% ' + lang">
    <x-heroicon-c-speaker-wave class="size-5" />
  </button>
  <span>{{ $this->text }}</span>
</span>
