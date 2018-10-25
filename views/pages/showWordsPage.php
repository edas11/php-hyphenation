<!doctype html>
<html>
<?php require('views/page-parts/head.php'); ?>
<body>
    <?php require('views/page-parts/main-header.php'); ?>
    <main>
        <h3>Hyphenated words list</h3>

        <form method="get" id="show-specific-word-form">
            <div class="form-group">
                <label for="wordToShow">Enter word you want to see or leave empty to show all</label>
                <input type="text" name="for" class="form-control" id="wordToShow" placeholder="Enter word">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <table class="table table-bordered">
            <tr>
                <th>Original word</th>
                <th>Hyphenated word</th>
            </tr>
            <tbody id="words-table">
            </tbody>
            <tr>
                <td colspan="2">
                    <a id="add-new" class="badge badge-primary" href="/hyphenation/change-hyphenation">Add new</a>
                </td>
            </tr>
        </table>
    </main>
    <script src="/static/main.js"/>
    <script type="text/javascript" >
    </script>
</body>
</html>