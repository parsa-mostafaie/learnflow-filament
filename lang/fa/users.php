<?php

return [
  'singular' => "کاربر",
  'plural' => "کاربران",

  "widgets" => [
    //
  ],
  "filters" => [
    'creation_range' => "بازه زمانی ایجاد",
    "updation_range" => "بازه زمانی تغییر",
    "role" => "نقش",
    "email_state" => [
      'label' => "وضعیت ایمیل",
      'verified' => 'تایید شده',
      'unverified' => 'تایید نشده',
    ],
    "email_verified_range" => "بازه زمانی تایید ایمیل",
  ],
  'columns' => [
    'name' => "نام",
    "avatar" => "آواتار",
    "role" => "نقش",
    "id" => "شناسه",
    "email" => "ایمیل",
    "email_verified_at" => "تاریخ تایید ایمیل",
    "courses_count" => 'تعداد دوره ها',
    "enrolled_courses_count" => 'تعداد دوره های ثبت نام شده',
    "created_at" => "تاریخ ایجاد",
    "updated_at" => "تاریخ تغییر",
  ],
  'roles' => [
    'user' => 'کاربر',
    'instructor' => 'مدرس',
    'manager' => 'مدیر',
    'admin' => 'مدیر کل',
    'developer' => 'توسعه دهنده',
    'unknown' => 'ناشناخته',
  ],
  'pages' =>
    [
      'edit' => "ویرایش کاربر",
      'view' => "نمایش کاربر"
    ],
  'sections' => [
    //
  ],
  'placeholders' => [
    //
  ],
];