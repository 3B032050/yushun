<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>帳號驗證</title>
</head>
<body>
<p>親愛的 {{ $master_name ? $master_name . ' 師傅' : '師傅' }}，</p>

<p>請點擊下方按鈕來驗證您的電子郵件地址：</p>

<p style="text-align: center;">
    <a href="{{ $url }}" style="background-color: #3490dc; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        驗證電子郵件
    </a>
</p>

<p>如果您並未註冊我們的服務，請忽略此信件。</p>
<p>感謝您使用我們的服務！</p>
</body>
</html>
