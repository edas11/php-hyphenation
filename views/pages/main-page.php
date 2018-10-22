<!doctype html>
<html>
<?php require('views/page-parts/head.php'); ?>
<body>
    <?php require('views/page-parts/main-header.php'); ?>
    <main>
        <ul class="list-group">
            <li class="list-group-item"><b>Navigation &darr;</b></li>
            <a class="list-group-item list-group-item-action" href="/hyphenation/show/words">Show hyphenated words</a>
            <a class="list-group-item list-group-item-action" href="/hyphenation/show/patterns">Show hyphenation patterns</a>
            <a class="list-group-item list-group-item-action" href="/hyphenation/hyphenated-words">Hyphenate words</a>
        </ul>
    </main>
    <script src="/static/main.js"/>
</body>
</html>