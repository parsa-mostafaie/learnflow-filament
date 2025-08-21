@props(['text' => ''])

<?php

use function Livewire\Volt\{state};
use App\Facades\TTS;

state(['text' => '', 'lang' => null, 'url' => null]);

$detectLanguage = function () {
    if ($this->lang == null) {
        $result = langof($this->text); // e.g. ["fa" => 0.84]

        $key = array_keys($result)[0];
        $lang = $key;

        $this->lang = ['lang' => $lang, 'confidence' => $result[$key], $lang => $result[$key]];
    }

    return $this->lang;
};

$generate = function () {
    if ($this->url == null) {
        $this->url = TTS::generate($this->text, $this->detectLanguage()['lang']);
    }

    return $this->url;
};
?>

<span x-data="{
    text: $wire.entangle('text').live,
    lang: null,
    confidence: null,
    url: null,
    speaking: false,
    audio: null,
    speak() {
        if (this.url) {
            if (!this.audio)
                this.audio = $el.getElementsByTagName('audio')[0]
            this.audio.play()
                .then(() => {
                    this.speaking = true;
                })
                .catch(error => {
                    console.error('Error playing audio:', error);
                    // Handle cases where autoplay is prevented by the browser
                    alert('Audio playback prevented by browser. Please interact with the page first.');
                });
        }
    },
    end() {
        this.speaking = false
    }
}" x-init="$wire.detectLanguage().then(result => {
    lang = result['lang'];
    confidence = result['confidence'];
});
$wire.generate().then(
    result => {
        url = result;
    }
);"
  class="flex justify-between items-center gap-1 w-4/5 mx-auto flex-wrap justify-self-auto">
  <button @click="speak" :class="speaking ? 'animate-pulse text-blue-600' : 'text-gray-500 hover:text-blue-600'"
    class="transition" :title="(confidence * 100).toFixed(1) + '% ' + lang">
    <x-heroicon-c-speaker-wave class="size-5" />
  </button>
  <span>{{ $this->text }}</span>
  <audio class="hidden" @ended="end" :src="url" preload></audio>
</span>
