<!doctype html>
<html>
<?php require('views/page-parts/head.php'); ?>
<body>
    <?php require('views/page-parts/main-header.php'); ?>
    <main>
        <h3>Hyphenate words</h3>

        <form id="post-form">
            <div class="form-group">
                <label for="wordsTohyphenate">Enter word to hyphenate (seperated by spaces)</label>
                <input type="text" name="words" class="form-control" id="wordsTohyphenate" placeholder="Enter word">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <div id="post-data">
            <h4>Skipped:</h4>
            <table class="table table-bordered" id="skipped">
                <tr>
                    <th>Original word</th>
                    <th>Hyphenated word</th>
                </tr>
            </table>

            <h4>Hyphenated:</h4>
            <table class="table table-bordered" id="hyphenated">
                <tr>
                    <th>Original word</th>
                    <th>Hyphenated word</th>
                </tr>
            </table>
        </div>
    </main>
    <script type="text/javascript" src="/static/main.js"/>
    <script type="text/javascript"></script>
</body>
</html>