<?php
// Facebook Conversion API إعدادات
$access_token = "EAALSY12HBSUBPWKbcAgcepOzW5zSsBFLFvVFvEm4FPNg1fBOtZAFuxZAFK8fUwIHM9vft8GZADUwJFeftL5uBCPrZAXpFgATyXhXDCeRn8hKtUeIlqjnr5GtyKolmbOd2UvGgHZC3wcBZB4zvZCria0ZBqv1lujcw8MQvx8KwvIU7V1tr177t0i5PtyPbXz1OZA1sZBwZDZD"; // حط التوكن ديالك
$pixel_id     = "1977260116428124";        // حط ID ديال البكسل

// استقبال البيانات من الفورم
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

// تجهيز البيانات للفيسبوك
$data = [
  "data" => [
    [
      "event_name" => "Purchase",  // الحدث: عملية شراء
      "event_time" => time(),
      "action_source" => "website",
      "user_data" => [
        "em" => hash('sha256', strtolower(trim($email))),
        "ph" => hash('sha256', preg_replace('/\D/', '', $phone))
      ],
      "custom_data" => [
        "currency" => "MAD",
        "value" => 0 // ممكن تعوضها بالثمن
      ]
    ]
  ]
];

// إرسال الطلب للفيسبوك
$ch = curl_init("https://graph.facebook.com/v18.0/$pixel_id/events?access_token=$access_token");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// تخزين الرد فملف للديباغ
file_put_contents("fb_log.txt", $response . PHP_EOL, FILE_APPEND);

// رسالة للزبون
echo "شكراً، تم إرسال طلبك بنجاح!";
