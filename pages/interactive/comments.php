<div>
    <?php
    // get all the comments

    foreach($comments as $comment) {
        $cmt = json_decode($comment);
    ?>
        <div class="card" style="width: 40%">
            <div class="card-body">
                <h5 class="card-title"><?php echo $cmt['name']; ?></h5>
                <p class="card-text">
                    <?php echo $cmt['content']; ?>
                </p>
            </div>
        </div>
    <?php } ?>
</div>