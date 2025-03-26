<div>
  <x-modal name="interactive-exercises">
    <div class="p-6 bg-white rounded-lg max-w-lg mx-auto text-gray-800">
      <h2 class="text-lg font-bold text-purple-700 mb-4">تمرین‌های تعاملی</h2>
      <p class="leading-relaxed text-justify">
        با یادگیری فعال علاوه بر کلاس درس، هنگام مطالعه نیز می‌توانید مطالب را بهتر درک کرده و به خاطر بسپارید. به این
        صورت که بیشتر با مطالب کتاب یا جزوه درگیر می‌شوید.
        یادگیری فعال در مطالعه به معنای خواندن مطالب با عزمی راسخ برای ارزیابی و درک ارتباط آن‌ها با نیازهای شما است.
        بازخوانی مطالب به تنهایی و بدون بکارگیری روش‌های درست برای یادگیری و درک مطالب درسی، راه موثری نیست.
        یادگیری فعال روشی تعاملی و انتقادی با محتوا است که می‌تواند در وقت شما نیز صرفه جویی کند.
      </p>
      <h3 class="text-md font-semibold text-gray-800 mt-4 mb-2">مزایای یادگیری فعال برای مطالعه</h3>
      <ul class="list-disc pl-5 space-y-2">
        <li>بهبود حفظ و یادآوری اطلاعات</li>
        <li>افزایش مشارکت و انگیزه دانش‌آموزان</li>
        <li>تقویت تفکر انتقادی و مهارت‌های حل مسئله</li>
        <li>بهبود مهارت‌های ارتباطی و همکاری</li>
        <li>آمادگی مؤثر برای آزمون‌ها و امتحانات</li>
      </ul>
      <h3 class="text-md font-semibold text-gray-800 mt-6 mb-2">چالش‌ها و ملاحظات اجرای یادگیری فعال برای مطالعه</h3>
      <ul class="list-disc pl-5 space-y-2">
        <li>نقشه ذهنی ایجاد کنید</li>
        <li>از سیستم لایتنر استفاده کنید</li>
        <li>بر منحنی فراموشی غلبه کنید</li>
      </ul>
      <div class="mt-6 flex justify-end">
        <x-danger-button type="button"
          wire:click="dispatch('close-modal', 'interactive-exercises')">بستن</x-danger-button>
      </div>
    </div>
  </x-modal>
</div>
