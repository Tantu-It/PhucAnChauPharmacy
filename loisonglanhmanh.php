<?php
session_start();
include 'includes/db.php';



$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phúc An Châu</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
        <link rel="stylesheet" href="assets/css/blog.css">
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <script src="assets/js/Trang_chu.js"></script>
</head>

<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>

    <main class="container">
        <section class="content">
            <h1>10 cách bảo vệ tim mạch mà bạn nên biết</h1>
            <p>Bảo vệ sức khỏe tim mạch là một trong những yếu tố quan trọng nhất trong việc duy trì cuộc sống khỏe mạnh và kéo dài tuổi thọ. Tim mạch đóng vai trò then chốt trong việc cung cấp oxy và dưỡng chất cho cơ thể, do đó, việc chăm sóc và bảo vệ tim mạch giúp ngăn ngừa các bệnh lý nguy hiểm như bệnh tim mạch, đột quỵ hay cao huyết áp. Vậy cần làm gì để trái tim luôn khỏe mạnh? Tìm hiểu ngay với Nhà Thuốc Phúc An Châu nhé.</p>
            <h2>Vì sao phải bảo vệ sức khỏe tim mạch?</h2>
            <p>Bảo vệ sức khỏe tim mạch là yếu tố thiết yếu để duy trì sự sống và sức khỏe lâu dài, vì tim là cơ quan quan trọng nhất trong việc cung cấp máu, oxy và dưỡng chất cho toàn cơ thể. Khi tim khỏe mạnh, nguy cơ mắc các bệnh nghiêm trọng như nhồi máu cơ tim, đột quỵ và các vấn đề về huyết áp được giảm thiểu. Đồng thời, một trái tim khỏe giúp cải thiện chất lượng cuộc sống, tăng cường năng lượng và ngăn ngừa những biến chứng nguy hiểm. Việc chăm sóc tim mạch đúng cách không chỉ giúp bạn duy trì sức khỏe ổn định mà còn đảm bảo một cuộc sống khỏe mạnh và lâu dài.</p>
            <div class="picture">
            <img src="assets/img/timmach.jpg" alt="" >
            </div>
            <h2>Hậu quả nếu trái tim không khỏe là gì?</h2>
            <p>Một trái tim yếu không chỉ làm giảm chất lượng cuộc sống mà còn gây ra hàng loạt vấn đề sức khỏe nguy hiểm, thậm chí đe dọa đến tính mạng.</p>
            <ul>
               <li>Nguy cơ nhồi máu cơ tim: Tim bị tổn thương hoặc tắc nghẽn mạch máu có thể dẫn đến nhồi máu cơ tim, gây tử vong đột ngột nếu không được cấp cứu kịp thời.</li>
               <li>Đột quỵ: Suy giảm lưu lượng máu lên não do bệnh tim mạch có thể dẫn đến đột quỵ, gây liệt, mất khả năng nói hoặc thậm chí tử vong.</li>
               <li>Suy tim: Khi tim không thể bơm đủ máu, cơ thể sẽ bị thiếu oxy và chất dinh dưỡng, dẫn đến mệt mỏi, khó thở, phù nề và ảnh hưởng nghiêm trọng đến chất lượng cuộc sống.</li>
               <li>Xơ vữa động mạch: Sự tích tụ cholesterol trong động mạch khiến mạch máu hẹp lại, tăng nguy cơ tắc nghẽn và giảm lưu lượng máu đến các cơ quan quan trọng.</li>
               <li>Cao huyết áp mãn tính: Không chăm sóc tim mạch có thể gây ra huyết áp cao, làm tăng nguy cơ tổn thương tim, thận, và các mạch máu lớn.</li>
               <li>Rối loạn nhịp tim: Tim mạch yếu có thể gây rối loạn nhịp tim như tim đập nhanh hoặc không đều, ảnh hưởng đến hiệu quả bơm máu.</li>
               <li>Tăng nguy cơ tử vong sớm: Bệnh tim mạch là nguyên nhân hàng đầu gây tử vong trên toàn cầu, thường xảy ra ở độ tuổi trung niên nếu không được chăm sóc đúng cách.</li>
               <li>Ảnh hưởng tâm lý: Sức khỏe tim mạch kém khiến bạn dễ mệt mỏi, lo âu, mất ngủ và giảm hiệu suất công việc.</li>
               <li>Biến chứng đến các cơ quan khác: Tim không khỏe mạnh sẽ làm tổn hại các cơ quan khác như thận, gan và phổi, gây ra nhiều bệnh lý nguy hiểm.</li>
               <li>Chi phí điều trị cao: Việc điều trị các bệnh lý tim mạch thường tốn kém và kéo dài, gây áp lực tài chính lớn cho người bệnh và gia đình.</li>
            <div class="picture">
            <img src="assets/img/benhtim.jpg" alt="" >
            </div>
            <li>Tăng nguy cơ tử vong sớm: Bệnh tim mạch là nguyên nhân hàng đầu gây tử vong trên toàn cầu, thường xảy ra ở độ tuổi trung niên nếu không được chăm sóc đúng cách.</li>
            <li>Ảnh hưởng tâm lý: Sức khỏe tim mạch kém khiến bạn dễ mệt mỏi, lo âu, mất ngủ và giảm hiệu suất công việc.</li>
            <li> Biến chứng đến các cơ quan khác: Tim không khỏe mạnh sẽ làm tổn hại các cơ quan khác như thận, gan và phổi, gây ra nhiều bệnh lý nguy hiểm.</li>
            <li> Chi phí điều trị cao: Việc điều trị các bệnh lý tim mạch thường tốn kém và kéo dài, gây áp lực tài chính lớn cho người bệnh và gia đình.</li>
            </ul>
            <h2>10 cách bảo vệ sức khỏe tim mạch bạn nên biết</h2>
            <p>Dưới đây là 10 cách bảo vệ sức khỏe tim mạch mà ai cũng nên biết để duy trì cuộc sống năng động, tràn đầy năng lượng và phòng tránh các rủi ro về sức khỏe.</p>
            <ul>
                <li>Ăn uống lành mạnh: Hãy tăng cường bổ sung rau xanh, trái cây và ngũ cốc nguyên hạt vào bữa ăn hàng ngày. Hạn chế tiêu thụ chất béo bão hòa, muối và đường để giảm nguy cơ bệnh tim mạch.</li>
                <li>Tập thể dục thường xuyên: Tập thể dục ít nhất 30 phút mỗi ngày với các hoạt động như đi bộ, chạy bộ, hoặc bơi lội để tăng cường tuần hoàn máu. Điều này giúp cải thiện sức khỏe tim mạch và duy trì cân nặng ổn định.</li>
                <li>Kiểm soát cân nặng: Giữ cân nặng hợp lý giúp giảm áp lực lên tim và giảm nguy cơ mắc các bệnh như cao huyết áp hay tiểu đường. Hãy kết hợp chế độ ăn uống khoa học và vận động thường xuyên để kiểm soát cân nặng.</li>
                <li>Ngừng hút thuốc: Hút thuốc là nguyên nhân chính gây xơ vữa động mạch và tăng nguy cơ nhồi máu cơ tim. Bỏ thuốc lá giúp cải thiện chức năng tim mạch và tăng tuổi thọ.</li>
                <li>Hạn chế rượu bia: Uống quá nhiều rượu có thể gây cao huyết áp và tổn thương cơ tim.</li>
                <li>Kiểm soát căng thẳng: Căng thẳng kéo dài có thể làm tăng nhịp tim và huyết áp, gây áp lực lớn lên tim. Hãy thư giãn bằng cách thiền định, tập yoga, hoặc tham gia các hoạt động giải trí để duy trì tâm lý ổn định.</li>
                <li>Ngủ đủ giấc: Thiếu ngủ có thể làm tăng nguy cơ cao huyết áp và rối loạn nhịp tim. Hãy ngủ đủ 7 - 8 tiếng mỗi ngày để trái tim được nghỉ ngơi và phục hồi.</li>
                <li>Theo dõi huyết áp và cholesterol: Huyết áp cao và cholesterol xấu là yếu tố nguy cơ lớn đối với bệnh tim mạch. Kiểm tra định kỳ giúp bạn phát hiện sớm và có biện pháp điều chỉnh kịp thời.</li>
                <li>Hạn chế đồ ăn nhanh và thực phẩm chế biến: Đồ ăn nhanh chứa nhiều chất béo bão hòa, muối và chất bảo quản gây hại cho tim. Thay vào đó, hãy chọn thực phẩm tươi sống và chế biến tại nhà để đảm bảo dinh dưỡng.</li>
                <li>Khám sức khỏe định kỳ: Định kỳ thăm khám sức khoẻ giúp bạn theo dõi tình trạng tim mạch và phát hiện các dấu hiệu bệnh từ sớm.</li>
            </ul>
        </section>

        <?php include'includes/recently.php'?>
        </main>
        <?php include 'includes/footer.php' ?>
         
    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('active');
        });
        
    </script>
</body>
</html>