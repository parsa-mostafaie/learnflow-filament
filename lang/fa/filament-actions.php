<?php

return [

  'approve' => [
    'single' => [
      'label' => 'تأیید',
      'modal' => [
        'heading' => 'آیا مطمئنی که ":label" رو می‌خوای تأیید کنی؟',
        'actions' => [
          'approve' => [
            'label' => 'تأیید کن',
          ],
        ],
      ],
      'notifications' => [
        'approved' => [
          'title' => 'با موفقیت تأیید شد 🎉',
        ],
      ],
    ],
    'multiple' => [
      'label' => 'تأیید انتخاب شده',
      'modal' => [
        'heading' => 'آیا مطمئنی می‌خوای همه :label رو تأیید کنی؟',
        'actions' => [
          'approve' => [
            'label' => 'تأیید کن',
          ],
        ],
      ],
      'notifications' => [
        'approved' => [
          'title' => 'همه موارد با موفقیت تأیید شدن!',
        ],
      ],
    ],
  ],
  'reject' => [
    'single' => [
      'label' => 'رد کردن',
      'modal' => [
        'heading' => 'مطمئنی می‌خوای ":label" رو رد کنی؟',
        'actions' => [
          'reject' => [
            'label' => 'رد کردن',
          ],
        ],
      ],
      'notifications' => [
        'rejected' => [
          'title' => 'با موفقیت رد شد!',
        ],
      ],
    ],
    'multiple' => [
      'label' => 'رد انتخاب شده',
      'modal' => [
        'heading' => 'آیا مطمئنی می‌خوای همه ":label" رو رد کنی؟',
        'actions' => [
          'reject' => [
            'label' => 'رد کن',
          ],
        ],
      ],
      'notifications' => [
        'rejected' => [
          'title' => 'همه موارد با موفقیت رد شدن!',
        ],
      ],
    ],
  ],
  'pending' => [
    'single' => [
      'label' => 'در انتظار بررسی',
      'modal' => [
        'heading' => 'آیا مطمئنی ":label" به حالت در انتظار بررسی بره؟',
        'actions' => [
          'pending' => [
            'label' => 'در انتظار بررسی',
          ],
        ],
      ],
      'notifications' => [
        'pended' => [
          'title' => 'با موفقیت به حالت در انتظار بررسی تغییر یافت!',
        ],
      ],
    ],
  ],

];
