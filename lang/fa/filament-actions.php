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
    'multiple' => [
      'label' => 'در انتظار بردن انتخاب شده',
      'modal' => [
        'heading' => 'آیا مطمئنی می‌خوای همه ":label" رو در حالت انتظار بذاری؟',
        'actions' => [
          'pending' => [
            'label' => 'منتظر بمونن',
          ],
        ],
      ],
      'notifications' => [
        'pending' => [
          'title' => 'همه موارد به حالت انتظار رفتن!',
        ],
      ],
    ],
  ],
  'learn' => [
    'single' => [
      'label' => 'یادگیری',
      'modal' => [
        //
      ],
      'notifications' => [
        //
      ],
    ],
  ],
  'report' => [
    'single' => [
      'label' => 'گزارش',
      'modal' => [
        //
      ],
      'notifications' => [
        //
      ],
    ],
  ],
  'enroll' => [
    'single' => [
      'label' => 'ثبت‌نام',
      'modal' => [
        'heading' => 'ثبت‌نام در :label',
        'actions' => [
          'enroll' => [
            'label' => 'ثبت‌نام',
          ],
        ],
      ],
      'notifications' => [
        'enrolled' => [
          'title' => 'با موفقیت ثبت‌نام شدید',
        ],
      ],
    ],
    'unenroll' => [
      'label' => 'لغو ثبت‌نام',
      'modal' => [
        'heading' => 'لغو ثبت‌نام از :label',
        'actions' => [
          'unenroll' => [
            'label' => 'لغو ثبت‌نام',
          ],
        ],
      ],
      'notifications' => [
        'unenrolled' => [
          'title' => 'ثبت‌نام شما لغو شد',
        ],
      ],
    ],
  ],

  // modes: single, demote, {in future: multiple, demote-multiple}
  'change-role' => [
    'single' => [
      'label' => 'ارتقا به :label',
      'modal' => [
        'heading' => 'ارتقا به :label',
        'actions' => [
          'change-role' => [
            'label' => 'ارتقا',
          ],
        ],
      ],
      'notifications' => [
        'change-roled' => [
          'title' => 'با موفقیت ارتقا داده شد',
        ],
      ],
    ],
    'demote' => [
      'label' => 'تنزل به :label',
      'modal' => [
        'heading' => 'تنزل به :label',
        'actions' => [
          'change-role' => [
            'label' => 'تنزل',
          ],
        ],
      ],
      'notifications' => [
        'change-roled' => [
          'title' => 'با موفقیت تنزل یافت',
        ],
      ],
    ],
  ],
];
