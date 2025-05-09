<?php

require_once(BASE_PATH . '/template/admin/layouts/head-tag.php')

    ?>

<section class="pt-3 pb-1 mb-2 border-bottom">
    <h1 class="h5">Create Article</h1>
</section>

<section class="row my-3">
    <section class="col-12">

        <form method="post" action="<?= url('admin/post/store') ?>" enctype="multipart/form-data"
            onsubmit="return syncQuill()">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title ..." required
                    autofocus>
            </div>

            <div class="form-group">
                <label for="cat_id">Category</label>
                <select name="cat_id" id="cat_id" class="form-control" required autofocus>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category['id'] ?>">
                            <?= $category['name'] ?>
                        </option>

                    <?php } ?>


                </select>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" class="form-control-file" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="summary">summary</label>
                <textarea maxlength="290" class="form-control" id="summary" name="summary" placeholder="summary ..." rows="3" required
                    autofocus></textarea>
            </div>

            <div class="form-group">
                <label for="body">body</label>
                <!-- <textarea class="form-control" id="body" name="body" placeholder="body ..." rows="5" required
                    autofocus></textarea> -->
                <!-- The Quill editor -->
                <div id="toolbar"></div>
                    <div id="editor" style="height: 200px;"></div>

                <!-- Hidden textarea that will be submitted -->
                <textarea name="body" id="body" style="display: none;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">store</button>
        </form>
    </section>
</section>


<?php

require_once(BASE_PATH . '/template/admin/layouts/footer.php')

    ?>