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
