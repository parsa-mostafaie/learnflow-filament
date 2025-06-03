<?php

return [
  'singular' => 'سوال',
  'plural' => 'سوالات',
  'columns' => [
    'question' => 'سوال',
    'answer' => 'پاسخ',
    'author' => 'نویسنده',
    'status' => 'وضعیت',
    'created_at' => 'ایجاد شده در',
    'updated_at' => 'به روز شده در',
  ],
  'statuses' => [
    'pending' => 'در انتظار',
    'approved' => 'تایید شده',
    'rejected' => 'رد شده',
  ],
  'filters' => [
    'author' => "نویسنده",
    "status" => "وضعیت",
    'creation_range' => "بازه زمانی ایجاد",
    "updation_range" => "بازه زمانی تغییر",
  ],
  'pages' =>
    [
      'edit' => "ویرایش سوال",
      'view' => "نمایش سوال"
    ],
  'messages' => [
    'import_success' => '{1} واردسازی سوال با موفقیت انجام شد و :count سطر وارد شد.|[2,*] واردسازی سوالات با موفقیت انجام شد و :count سطر وارد شد.',
  ],
  'importer' => [
    'question' => 'سوال',
    'answer' => 'پاسخ',
  ],
];
