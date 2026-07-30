<?php
$inserts = [
    "INSERT INTO `video_terkait_db` (`id`, `judul`, `url_video`, `thumbnail`, `created_at`) VALUES (1, 'print (''halooo'')', 'https://www.youtube.com/watch?v=3Bu3vUMmVfs', 'assets/images/video/video_1784541367_6778.png', '2026-07-20 09:56:07')",
    "INSERT INTO `video_terkait_db` (`id`, `judul`, `url_video`, `thumbnail`, `created_at`) VALUES (2, 'ini video keren', 'assets/videos/video_1784778740_9047.mp4', 'assets/images/video/video_thumb_1784778740_9615.jpeg', '2026-07-23 03:52:20')",
    "INSERT INTO `video_terkait_db` (`id`, `judul`, `url_video`, `thumbnail`, `created_at`) VALUES (3, 'PRASPA 2026', 'https://www.youtube.com/watch?v=XmO5g8QL_yY', 'assets/images/video/video_1785297128_7689.jpg', '2026-07-29 03:52:08')"
];
foreach($inserts as $ins) {
    try {
        DB::insert($ins);
    } catch(Exception $e) {}
}
echo "Video inserted.";
