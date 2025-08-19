<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'error',
        title: '錯誤',
        text: '{{ $message ?? "系統發生錯誤，請稍後再試" }}'
    }).then(() => {
        window.history.back(); // 彈窗後返回上一頁
    });
</script>
