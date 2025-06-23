<?php

return [
  'singular' => "دوره",
  'plural' => "دوره ها",
  "widgets" => [
    'total_count' => "تعداد کل دوره ها",
    'total_enrolls' => "تعداد کل ثبت نام ها",
  ],
  "filters" => [
    'deletion_range' => "بازه زمانی حذف",
    'author' => "کاربر",
    'creation_range' => "بازه زمانی ایجاد",
    "updation_range" => "بازه زمانی تغییر",
    "my_courses" => "فقط دوره های من",
    "enrolled" => "فقط ثبت نام شده ها"
  ],
  'columns' => [
    'title' => "عنوان",
    'slug' => "اسلاگ",
    "thumbnail" => "تصویر",
    "description" => "توضیحات",
    "author" => "مالک",
    "id" => "شناسه",
    "deleted_at" => "تاریخ حذف",
    "created_at" => "تاریخ ایجاد",
    "updated_at" => "تاریخ تغییر",
    "enrolls_count" => "تعداد کاربر های ثبت نام شده",
    "questions_count" => "تعداد سوالات",
    "all_questions_count" => "تعداد تمام سوالات",
    "approved_questions_count" => "تعداد سوالات تایید شده",
    "rejected_questions_count" => "تعداد سوالات رد شده",
    "pending_questions_count" => "تعداد سوالات در انتظار تایید"
  ],
  'pages' =>
    [
      'edit' => "ویرایش دوره",
      'view' => "نمایش دوره"
    ],
  'sections' => [
    'main_info' => 'اطلاعات اصلی',
    'main_info_desc' => 'جزئیات کلی دوره را ببینید.',
    'meta' => 'اطلاعات متا',
    "details" => "جزئیات"
  ],
  'placeholders' => [
    'title' => 'عنوان ثبت نشده! 😶‍🌫️',
    'slug' => 'نامک خالیه 🕵️‍♂️',
    'description' => 'توضیحی موجود نیست 🤷‍♂️',
    'created_at' => 'تاریخ ثبت نشده 😵‍💫',
    'updated_at' => 'تاریخ آپدیت موجود نیست 😴',
    'deleted_at' => 'حذف نشده 😶‍🌫️',
  ],
];