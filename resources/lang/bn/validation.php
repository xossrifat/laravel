<?php

return [
    /*
    |--------------------------------------------------------------------------
    | যাচাইকরণ ভাষা লাইনগুলি
    |--------------------------------------------------------------------------
    |
    | নিম্নলিখিত ভাষা লাইনগুলি যাচাইকরণ ক্লাস দ্বারা ব্যবহৃত ডিফল্ট ত্রুটি
    | বার্তাগুলি ধারণ করে। কয়েকটি নিয়ম রয়েছে যেগুলি বিভিন্ন আকারের 
    | বার্তা জোগার দেয়। নিজের প্রয়োজনে অনুসারে এগুলি পরিবর্তন করুন।
    |
    */

    'accepted' => ':attribute গ্রহণ করতে হবে।',
    'accepted_if' => ':attribute অবশ্যই গ্রহণ করতে হবে যখন :other হল :value।',
    'active_url' => ':attribute একটি বৈধ URL নয়।',
    'after' => ':attribute অবশ্যই :date এর পরের একটি তারিখ হতে হবে।',
    'after_or_equal' => ':attribute অবশ্যই :date এর পরে বা সমান একটি তারিখ হতে হবে।',
    'alpha' => ':attribute শুধুমাত্র অক্ষর ধারণ করতে পারে।',
    'alpha_dash' => ':attribute শুধুমাত্র অক্ষর, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর ধারণ করতে পারে।',
    'alpha_num' => ':attribute শুধুমাত্র অক্ষর এবং সংখ্যা ধারণ করতে পারে।',
    'array' => ':attribute অবশ্যই একটি অ্যারে হতে হবে।',
    'before' => ':attribute অবশ্যই :date এর আগের একটি তারিখ হতে হবে।',
    'before_or_equal' => ':attribute অবশ্যই :date এর আগে বা সমান একটি তারিখ হতে হবে।',
    'between' => [
        'numeric' => ':attribute অবশ্যই :min এবং :max এর মধ্যে হতে হবে।',
        'file' => ':attribute অবশ্যই :min এবং :max কিলোবাইটের মধ্যে হতে হবে।',
        'string' => ':attribute অবশ্যই :min এবং :max অক্ষরের মধ্যে হতে হবে।',
        'array' => ':attribute অবশ্যই :min এবং :max আইটেমের মধ্যে হতে হবে।',
    ],
    'boolean' => ':attribute ক্ষেত্রটি অবশ্যই সত্য বা মিথ্যা হতে হবে।',
    'confirmed' => ':attribute নিশ্চিতকরণ মেলে না।',
    'current_password' => 'পাসওয়ার্ড ভুল।',
    'date' => ':attribute একটি বৈধ তারিখ নয়।',
    'date_equals' => ':attribute অবশ্যই :date এর সমান একটি তারিখ হতে হবে।',
    'date_format' => ':attribute ফরম্যাট :format এর সাথে মেলে না।',
    'declined' => ':attribute অবশ্যই প্রত্যাখ্যান করতে হবে।',
    'declined_if' => ':attribute অবশ্যই প্রত্যাখ্যান করতে হবে যখন :other হল :value।',
    'different' => ':attribute এবং :other অবশ্যই আলাদা হতে হবে।',
    'digits' => ':attribute অবশ্যই :digits সংখ্যা হতে হবে।',
    'digits_between' => ':attribute অবশ্যই :min এবং :max সংখ্যার মধ্যে হতে হবে।',
    'dimensions' => ':attribute অবৈধ চিত্র মাত্রা রয়েছে।',
    'distinct' => ':attribute ক্ষেত্রের একটি সদৃশ মান আছে।',
    'email' => ':attribute অবশ্যই একটি বৈধ ইমেইল ঠিকানা হতে হবে।',
    'ends_with' => ':attribute নিম্নলিখিত একটির সাথে শেষ হতে হবে: :values।',
    'exists' => 'নির্বাচিত :attribute অবৈধ।',
    'file' => ':attribute অবশ্যই একটি ফাইল হতে হবে।',
    'filled' => ':attribute ক্ষেত্রের একটি মান থাকতে হবে।',
    'gt' => [
        'numeric' => ':attribute অবশ্যই :value এর চেয়ে বড় হতে হবে।',
        'file' => ':attribute অবশ্যই :value কিলোবাইটের চেয়ে বড় হতে হবে।',
        'string' => ':attribute অবশ্যই :value অক্ষরের চেয়ে বড় হতে হবে।',
        'array' => ':attribute অবশ্যই :value আইটেমের চেয়ে বেশি হতে হবে।',
    ],
    'gte' => [
        'numeric' => ':attribute অবশ্যই :value এর চেয়ে বড় বা সমান হতে হবে।',
        'file' => ':attribute অবশ্যই :value কিলোবাইটের চেয়ে বড় বা সমান হতে হবে।',
        'string' => ':attribute অবশ্যই :value অক্ষরের চেয়ে বড় বা সমান হতে হবে।',
        'array' => ':attribute এ অবশ্যই :value আইটেম বা তার বেশি থাকতে হবে।',
    ],
    'image' => ':attribute অবশ্যই একটি চিত্র হতে হবে।',
    'in' => 'নির্বাচিত :attribute অবৈধ।',
    'in_array' => ':attribute ক্ষেত্র :other এ বিদ্যমান নেই।',
    'integer' => ':attribute অবশ্যই একটি পূর্ণসংখ্যা হতে হবে।',
    'ip' => ':attribute অবশ্যই একটি বৈধ IP ঠিকানা হতে হবে।',
    'ipv4' => ':attribute অবশ্যই একটি বৈধ IPv4 ঠিকানা হতে হবে।',
    'ipv6' => ':attribute অবশ্যই একটি বৈধ IPv6 ঠিকানা হতে হবে।',
    'json' => ':attribute অবশ্যই একটি বৈধ JSON স্ট্রিং হতে হবে।',
    'lt' => [
        'numeric' => ':attribute অবশ্যই :value এর চেয়ে কম হতে হবে।',
        'file' => ':attribute অবশ্যই :value কিলোবাইটের চেয়ে কম হতে হবে।',
        'string' => ':attribute অবশ্যই :value অক্ষরের চেয়ে কম হতে হবে।',
        'array' => ':attribute এ অবশ্যই :value আইটেমের চেয়ে কম থাকতে হবে।',
    ],
    'lte' => [
        'numeric' => ':attribute অবশ্যই :value এর চেয়ে কম বা সমান হতে হবে।',
        'file' => ':attribute অবশ্যই :value কিলোবাইটের চেয়ে কম বা সমান হতে হবে।',
        'string' => ':attribute অবশ্যই :value অক্ষরের চেয়ে কম বা সমান হতে হবে।',
        'array' => ':attribute এ অবশ্যই :value আইটেমের বেশি থাকতে পারবে না।',
    ],
    'max' => [
        'numeric' => ':attribute অবশ্যই :max এর চেয়ে বড় হতে পারবে না।',
        'file' => ':attribute অবশ্যই :max কিলোবাইটের চেয়ে বড় হতে পারবে না।',
        'string' => ':attribute অবশ্যই :max অক্ষরের চেয়ে বড় হতে পারবে না।',
        'array' => ':attribute এ অবশ্যই :max এর বেশি আইটেম থাকতে পারবে না।',
    ],
    'mimes' => ':attribute অবশ্যই নিম্নলিখিত ধরনের ফাইল হতে হবে: :values।',
    'mimetypes' => ':attribute অবশ্যই নিম্নলিখিত ধরনের ফাইল হতে হবে: :values।',
    'min' => [
        'numeric' => ':attribute অবশ্যই কমপক্ষে :min হতে হবে।',
        'file' => ':attribute অবশ্যই কমপক্ষে :min কিলোবাইট হতে হবে।',
        'string' => ':attribute অবশ্যই কমপক্ষে :min অক্ষর হতে হবে।',
        'array' => ':attribute এ অবশ্যই কমপক্ষে :min আইটেম থাকতে হবে।',
    ],
    'multiple_of' => ':attribute অবশ্যই :value এর গুণিতক হতে হবে।',
    'not_in' => 'নির্বাচিত :attribute অবৈধ।',
    'not_regex' => ':attribute ফরম্যাট অবৈধ।',
    'numeric' => ':attribute অবশ্যই একটি সংখ্যা হতে হবে।',
    'password' => 'পাসওয়ার্ড ভুল।',
    'present' => ':attribute ক্ষেত্র অবশ্যই উপস্থিত থাকতে হবে।',
    'regex' => ':attribute ফরম্যাট অবৈধ।',
    'required' => ':attribute ক্ষেত্র প্রয়োজন।',
    'required_if' => ':attribute ক্ষেত্র প্রয়োজন যখন :other হল :value।',
    'required_unless' => ':attribute ক্ষেত্র প্রয়োজন যদি না :other :values এ থাকে।',
    'required_with' => ':attribute ক্ষেত্র প্রয়োজন যখন :values উপস্থিত থাকে।',
    'required_with_all' => ':attribute ক্ষেত্র প্রয়োজন যখন :values উপস্থিত থাকে।',
    'required_without' => ':attribute ক্ষেত্র প্রয়োজন যখন :values উপস্থিত থাকে না।',
    'required_without_all' => ':attribute ক্ষেত্র প্রয়োজন যখন :values এর কোনটিই উপস্থিত থাকে না।',
    'prohibited' => ':attribute ক্ষেত্র নিষিদ্ধ।',
    'prohibited_if' => ':attribute ক্ষেত্র নিষিদ্ধ যখন :other হল :value।',
    'prohibited_unless' => ':attribute ক্ষেত্র নিষিদ্ধ যদি না :other :values এ থাকে।',
    'prohibits' => ':attribute ক্ষেত্র :other কে উপস্থিত থাকতে বাধা দেয়।',
    'same' => ':attribute এবং :other অবশ্যই মিলতে হবে।',
    'size' => [
        'numeric' => ':attribute অবশ্যই :size হতে হবে।',
        'file' => ':attribute অবশ্যই :size কিলোবাইট হতে হবে।',
        'string' => ':attribute অবশ্যই :size অক্ষর হতে হবে।',
        'array' => ':attribute অবশ্যই :size আইটেম ধারণ করতে হবে।',
    ],
    'starts_with' => ':attribute নিম্নলিখিত একটি দিয়ে শুরু হতে হবে: :values।',
    'string' => ':attribute অবশ্যই একটি স্ট্রিং হতে হবে।',
    'timezone' => ':attribute অবশ্যই একটি বৈধ টাইমজোন হতে হবে।',
    'unique' => ':attribute ইতিমধ্যে নেওয়া হয়েছে।',
    'uploaded' => ':attribute আপলোড করতে ব্যর্থ হয়েছে।',
    'url' => ':attribute অবশ্যই একটি বৈধ URL হতে হবে।',
    'uuid' => ':attribute অবশ্যই একটি বৈধ UUID হতে হবে।',

    /*
    |--------------------------------------------------------------------------
    | কাস্টম যাচাইকরণ ভাষা লাইনগুলি
    |--------------------------------------------------------------------------
    |
    | এখানে আপনি কাস্টম যাচাইকরণ বার্তা নির্দিষ্ট করতে পারেন "attribute.rule"
    | কনভেনশন ব্যবহার করে প্রতিটি ক্ষেত্র এবং নিয়মের জন্য। এটি আপনাকে
    | একটি সম্পূর্ণ নতুন নিয়ম নির্দিষ্ট না করেও কাস্টম ভাষা নির্দিষ্ট করতে দেয়।
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | কাস্টম যাচাইকরণ অ্যাট্রিবিউট
    |--------------------------------------------------------------------------
    |
    | নিম্নলিখিত ভাষা লাইনগুলি ব্যবহার করা হয় :attribute প্লেসহোল্ডার পরিবর্তন করতে
    | আরও পাঠযোগ্য কিছু যেমন "ইমেইল ঠিকানা" বা "ইউজারনেম"। এটি
    | আমাদের বার্তাগুলি আরও উপদেশমূলক করতে সাহায্য করে।
    |
    */

    'attributes' => [],
]; 