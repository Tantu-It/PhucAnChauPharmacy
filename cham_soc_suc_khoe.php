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

</head>



<body>

    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>

    <main class="container">

        <section class="content">

            <h1>15 mẹo bảo vệ sức khỏe mùa nắng nóng ai cũng nên biết</h1>

            <p>Với tiết trời oi bức kéo dài như hiện nay, nếu không biết bảo vệ sức khỏe mùa nắng nóng đúng cách và hiệu quả sẽ rất dễ dẫn đến mệt mỏi hoặc thậm chí sốc nhiệt. Để biết làm thế nào giữ gìn sức khỏe trong mùa hè này, Nhà thuốc Phúc An Châu mời bạn cùng tham khảo ngay 15 mẹo nhỏ sau.</p>

            <h2>Lựa chọn thời điểm tránh nắng</h2>

            <p>Thời điểm thích hợp nhất để tránh nắng, bảo vệ sức khỏe mùa nắng nóng theo chuyên gia là từ 10h sáng đến 2h chiều. Đây là lúc tia UV đo được trong ánh nắng đạt mức cao nhất nên để tránh say nắng, mệt mỏi,… bạn nên ở trong nhà vào thời điểm này để nghỉ ngơi, tránh nắng nóng.</p>

            <div class="picture">

            <img src="assets/img/nang.jpg" alt="nang" >

            <p ><i>Không nên ra ngoài trong khoảng 10h sáng đến 2h chiều vì đây là lúc mặt trời gay gắt nhất</i></p>

            </div>

            <h2>Uống đủ nước</h2>

            <p>Mùa nắng nóng là khi cơ thể đổ nhiều mồ hôi liên tục, cơ thể dễ bị mất nước dẫn đến chóng mặt, mệt mỏi, uể oải,… Lúc này, cách giúp bạn bảo vệ sức khỏe mùa nắng nóng hiệu quả là hãy uống thật nhiều nước. Bạn nên ưu tiên uống nước lọc, nước trái cây tươi, nước dừa,… sẽ tốt cho sức khỏe và giải khát tốt hơn. Nên hạn chế các loại nước ngọt có gas, cà phê, nước trái cây đóng chai,…</p>

            <h2>Hạn chế tiếp xúc với ánh nắng mặt trời</h2>

            <p>Thêm một cách giúp bạn bảo vệ sức khỏe mùa nắng nóng nữa là hạn chế tiếp xúc với ánh nắng mặt trời càng nhiều càng tốt. Tuy rằng ánh nắng có thể thúc đẩy cơ thể tổng hợp vitamin D hiệu quả nhưng với thời tiết oi nóng kéo dài như hiện nay, mức độ tia UV từ mặt trời có thể làm tổn thương và tăng nguy cơ ung thư da. Vì vậy, hãy hạn chế ra ngoài khi trời nắng gắt và sử dụng thêm kem chống nắng có SPF từ 35 trở lên, bạn nhé.</p>

            <h2>Không để nhiệt độ cơ thể thay đổi đột ngột</h2>

            <p>Nhiệt độ cơ thể thay đổi đột ngột là một trong những nguyên nhân dẫn đến mệt mỏi, thậm chí đột quỵ khi mạch máu không đáp ứng điều kiện co giãn thích hợp. Chính vì thế, để bảo vệ sức khỏe mùa nắng nóng, bạn cần tránh tuyệt đối việc đi nắng về tắm, rửa chân tay hoặc bật máy lạnh ngay. Thay vào đó, hãy để cơ thể nghỉ ngơi, ổn định nhiệt độ khoảng 30 – 45 phút trước khi tắm hoặc mở máy lạnh.</p>

            <div class="picture">

            <img src="assets/img/tam.jpg" alt="nang" >

            <p ><i>Đi nắng về nên tránh tắm gội ngay vì có nguy cơ sốc nhiệt gây đột quỵ</i></p>

            </div>

            <h2>Chú ý đến vệ sinh an toàn thực phẩm</h2>

            <p>Vào mùa nắng nóng, thực phẩm rất dễ bị ôi thiu do nhiệt độ tăng cao. Khi này, để bảo vệ sức khỏe mùa nắng nóng cho bản thân và cả gia đình, bạn cần chú ý bảo quản đồ ăn trong ngăn mát hoặc ngăn đá tủ lạnh, không nên để thức ăn ở ngoài quá lâu, luôn đậy thức ăn cẩn thận, chú ý hạn sử dụng của thực phẩm tươi sống,…</p>

            <h2>Phòng bệnh truyền nhiễm mùa nắng nóng</h2>

            <p>Mùa nóng là thời điểm các bệnh truyền nhiễm như bệnh tay chân miệng, bệnh quai bị, sởi,… rất dễ lây nhiễm nên bạn cần cẩn thận phòng bệnh hơn chữa bệnh, đặc biệt là khi gia đình có người lớn tuổi và trẻ em. Tăng cường sức đề kháng bên trong bằng chế độ dinh dưỡng, cách ly sớm nhất khỏi nguồn lây bệnh, luôn rửa sạch tay chân trước khi ăn và sau khi đi vệ sinh,… để phòng bệnh truyền nhiễm vào mùa nắng này.</p>

            <h2>Bảo vệ da khỏi sự tấn công từ côn trùng</h2>

            <p>Khi có hoạt động ngoài trời, đặc biệt ở khu vực có khí hậu nóng ẩm bạn nên sử dụng thêm thuốc, kem bôi chống côn trùng, chống muỗi để bảo vệ sức khỏe mùa nắng nóng. Biện pháp này sẽ giảm hiệu quả nguy cơ mắc nhiều bệnh truyền nhiễm do côn trùng, muỗi như sốt xuất huyết, các bệnh về virus,…</p>

            <h2>Sử dụng không gian có máy lạnh</h2>

            <p>Khi thời tiết có mức nhiệt quá cao, việc tận dụng các không gian mát mẻ, có máy lạnh như thư viện, nơi công cộng, trung tâm thương mại,… sẽ giúp bạn cảm thấy dễ chịu hơn nhiều so với việc dùng quạt đơn thuần. Hơn hết, tận dụng những không gian này còn tiết kiệm điện, đề phòng nguy cơ cháy nổ do quá tải điện mà vẫn bảo vệ sức khỏe mùa nắng nóng hiệu quả.</p>

            <h2>Bổ sung thêm các bữa ăn nhẹ trong ngày</h2>

            <p>Để giữ sức khỏe ổn định, cơ thể luôn có đủ năng lượng để làm việc, hoạt động,… bạn nên bổ sung thêm các bữa ăn nhẹ, bữa phụ trong ngày. Bạn có thể cân nhắc những món giàu dinh dưỡng và nhẹ bụng như salad trái cây, salad rau củ quả, sushi hoặc các thực phẩm dễ tiêu hóa khác. Việc bổ sung các bữa ăn nhẹ sẽ thúc đẩy trao đổi chất trong cơ thể, hạn chế cảm giác thèm ăn và giúp bạn luôn tràn đầy năng lượng từ đầu đến cuối ngày.</p>

            <div class="picture">

            <img src="assets/img/rau.jpg" alt="nang" >

            <p ><i>Để bảo vệ sức khỏe mùa nắng nóng, bạn không nên bỏ qua các bữa ăn phụ</i></p>

            </div>

            <h2>Lựa chọn quần áo phù hợp với thời tiết</h2>

            <p>Để cảm thấy mát mẻ, dễ chịu hơn vào tiết trời nắng nóng như hiện nay, bạn cần ưu tiên các chất liệu quần áo thoáng mát, thoáng khí, thấm hút mồ hôi hiệu quả như cotton, vải thun,… Bạn nên tránh những trang phục bó sát cơ thể, thay vào đó là những bộ quần áo rộng rãi sẽ tốt và dễ chịu hơn đấy.</p>

            <h2>Cách bảo vệ sức khỏe mùa nắng nóng - Tắm nước mát</h2>

            <p>Giữ cơ thể sạch sẽ bằng cách tắm rửa bằng nước mát là cách bảo vệ sức khỏe mùa nắng nóng rất hiệu quả. Thay vì tắm bằng nước ấm như trước, bạn nên đổi qua nước mát, nước lạnh để giảm nhiệt tốt hơn, cơ thể mát mẻ dễ chịu hơn sau khi tắm. Lưu ý không tắm bằng nước mát, nước lạnh ngay khi cơ thể đang nóng bức, mới từ ngoài trở về,…</p>

            <h2>Kiểm tra sức khỏe</h2>

            <p>Vào mùa nắng nóng, đặc biệt là với người lớn tuổi và trẻ em, bạn nên chú ý kiểm tra sức khỏe cho họ thường xuyên hơn. Với người cao tuổi có bệnh nền như đái tháo đường, cao huyết áp,… thì cần đo chỉ số đường huyết, huyết áp đều đặn để chắc rằng, không khí oi nóng có ảnh hưởng đến các chỉ số, sức khỏe của người thân hay không. Theo khuyến cáo, một số dấu hiệu cần chú ý để bảo vệ sức khỏe mùa nắng nóng gồm:</p>

            <ul>

                <li>Đổ nhiều mồ hôi;</li>

                <li>Nhiệt độ cơ thể cao trên 39.4°C;</li>

                <li>Da nhợt nhạt hoặc ửng đỏ;</li>

                <li>Mệt mỏi thường xuyên;</li>

                <li>Đau đầu;</li>

                <li>Hoa mắt chóng mặt;</li>

                <li>Ngất xỉu, bất tỉnh;</li>

                <li>Mạch nhanh nhưng yếu;</li>

                <li>Buồn nôn, nôn mửa.</li>

            </ul>

            <div class="picture">

            <img src="assets/img/suckhoe.jpg" alt="nang" >

            <p ><i>Người lớn tuổi có biểu hiện hoa mắt chóng mặt, đau đầu,... cần hết sức chú ý đến sức khỏe</i></p>

            </div>

            <h2>Hạn chế hoạt động ngoài trời</h2>

            <p>Thời điểm thích hợp để bạn hoạt động ngoài trời trong mùa nắng nóng này là từ 4 – 7 giờ sáng. Còn lại những khung giờ khác nên ở trong nhà để tránh nắng, tránh nóng, bảo vệ sức khỏe bản thân.</p>

            <h2>Hoạt động giải nhiệt mùa hè</h2>

            <p>Mùa hè oi nóng bạn nên tránh hoạt động ngoài trời, thay vào đó có thể thử những hoạt động giải nhiệt, giải trí cũng rất thú vị khác như:</p>

            <ul>

                <li>Chơi board game hoặc xếp hình;</li>

                <li>Tập bơi trong nhà;</li>

                <li>Xem phim, chơi game;</li>

                <li>Đi mua sắm;</li>

                <li>Chơi bowling hoặc những khu vui chơi trong nhà.</li>

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