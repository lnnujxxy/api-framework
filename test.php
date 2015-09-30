<?php
$image = new Gmagick('assets/1.jpg');
//模糊滤镜效果,参数为半径，标准偏差
$image->blurimage(40, 40);
$image->write('assets/blur_1.jpg');
echo "ok";