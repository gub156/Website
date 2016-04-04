<?php session_start(); ?>
<ul>
    <li><a href="<?php echo $_SESSION['backFile'];?>medias"?>REFAST News</a></li>
    <li><a href="<?php echo $_SESSION['backFile'];?>12_medias/pressReview"><?php echo PRESS_REVIEW;?></a></li>
    <li><a href="<?php echo $_SESSION['backFile'];?>12_medias/pressRelease"><?php echo PRESS_RELEASE;?></a></li>
    <li><a href="<?php echo $_SESSION['backFile'];?>12_medias/kitMedia"><?php echo KIT_MEDIA;?></a></li>
    <li><a href="<?php echo $_SESSION['backFile'];?>12_medias/videos"><?php echo VIDEOS_PAGE;?></a></li>
</ul>