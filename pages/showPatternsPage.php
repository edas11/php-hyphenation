<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="/static/style.css"/>
</head>
<body>
    <nav>
        <h1><a href="/hyphenation">Hyphenator</a></h1>
    </nav>
    <main>
        <h3>Patterns list</h3>
        <?php
            if (array_key_exists('page', $data)) {
                $page = (int)$data['page'];
            } else {
                $page = 1;
            }
        ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php if ($page >1) { ?>
                <li class="page-item"><a class="page-link" href="/hyphenation/show/patterns?page=<?= $page-1 ?>">Previous</a></li>
                <li class="page-item"><a class="page-link" href="/hyphenation/show/patterns?page=<?= $page-1 ?>"><?= $page-1 ?></a></li>
                <?php } ?>
                <li class="page-item active"><a class="page-link" href=""><?= $page ?></a></li>
                <li class="page-item"><a class="page-link" href="/hyphenation/show/patterns?page=<?= $page+1 ?>"><?= $page+1 ?></a></li>
                <li class="page-item"><a class="page-link" href="/hyphenation/show/patterns?page=<?= $page+1 ?>">Next</a></li>
            </ul>
        </nav>
        <table class="table table-bordered">
            <tr>
                <th>Nr.</th>
                <th>Pattern</th>
            </tr>
            <?php foreach ($data['result'] as $index => $pattern) { ?>
                <tr>
                    <td><?= $index ?></td>
                    <td><?= $pattern ?></td>
                </tr>
            <?php } ?>
        </table>
    </main>
</body>
</html>
<script src="/static/main.js"/>