<!doctype html>
<html>
<?php require('views/page-parts/head.php'); ?>
<body>
    <?php require('views/page-parts/main-header.php'); ?>
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
                <li class="page-item">
                    <a class="page-link" href="/hyphenation/show/patterns?page=<?php echo $page-1; ?>">Previous</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="/hyphenation/show/patterns?page=<?php echo $page-1; ?>"><?php echo $page-1; ?></a>
                </li>
                <?php } ?>
                <li class="page-item active">
                    <a class="page-link" href=""><?php echo $page; ?></a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="/hyphenation/show/patterns?page=<?php echo $page+1; ?>"><?php echo $page+1; ?></a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="/hyphenation/show/patterns?page=<?php echo $page+1; ?>">Next</a>
                </li>
            </ul>
        </nav>

        <table class="table table-bordered">
            <tr>
                <th>Nr.</th>
                <th>Pattern</th>
            </tr>
            <?php foreach ($data['result'] as $index => $pattern) { ?>
                <tr>
                    <td><?php echo $index; ?></td>
                    <td><?php echo $pattern; ?></td>
                </tr>
            <?php } ?>
        </table>
    </main>
    <script src="/static/main.js"/>
</body>
</html>