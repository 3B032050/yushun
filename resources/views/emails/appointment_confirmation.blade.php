<!-- resources/views/emails/appointment_confirmation.blade.php -->
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>預約確認</title>
</head>
<body>
<p>親愛的 {{ $user_name ?? '未知用戶' }}，</p>
<p>師傅: {{ $master_name ?? '未知師傅' }}</p>
<p>狀態:
    @if(isset($status) && $status == 1)
        您的預約已成功安排。
    @elseif(isset($status) && $status == 0)
        您的預約正在等待確認，請耐心等候。
    @elseif(isset($status) && $status == 3)
        很抱歉，您的預約未被通過，歡迎重新預約或洽詢客服。
    @elseif(isset($status) && $status == 4)
        很抱歉，您的預約已被取消，歡迎重新預約或洽詢客服。
    @else
        未知狀態
    @endif
</p>




<p>預約日期: {{ $service_date ?? '未指定日期' }}</p>
<p>預約時間: {{ $appointment_time ?? '未指定時間' }}</p>
<p>服務地址: {{ $service_address ?? '未提供地址' }}</p>
<p>感謝您的預約！</p>
</body>
</html>
