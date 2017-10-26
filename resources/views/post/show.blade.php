<?php
use App\User;
?>
<div class="blog-view">
    <table id="" class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th>Title</th>
                <td><?= $post->title ?></td>
            </tr>
            <tr>
                <th>Url</th>
                <td><?= $post->url ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?= $post->description ?></td>
            </tr>
        </tbody>
    </table>
    
    <?php if(!empty($post->userPost)) { ?>
    <p><b>Vote History:</b></p>
    <?php foreach($post->userPost as $voters) { ?>
                <p>
                    <?php 
                        $user = User::findOrFail($voters->user_id); 
                        echo $user->name.": ";
                        echo ($voters->vote_status == 1) ? '<span class="glyphicon glyphicon-thumbs-up"></span>' : '<span class="glyphicon glyphicon-thumbs-down"></span>';
                    ?>
                </p>
    <?php } } ?>
</div>