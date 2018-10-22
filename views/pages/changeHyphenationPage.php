<!doctype html>
<html>
<?php require('views/page-parts/head.php'); ?>
<body>
    <?php require('views/page-parts/main-header.php'); ?>
    <main>
        <h3>Change hyphenation or add new</h3>
        <?php
        if (array_key_exists('for', $data)) {
            $default = $data['for'];
        } else {
            $default = '';
        }
        ?>
        <form id="changeForm" method="post">
            <div class="form-group">
                <label for="wordToShow">Enter word</label>
                <input type="text" name="for" class="form-control" id="wordToShow" placeholder="Enter word"
                       value="<?php echo htmlspecialchars($default); ?>">
            </div>
            <div class="form-group">
                <label for="wordToShow">Enter new hyphenation</label>
                <input type="text" name="new" class="form-control" id="wordToShow" placeholder="Enter word">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </main>
    <script type="text/javascript" src="/static/main.js"/>
    <script type="text/javascript" >
    </script>
</body>
</html>