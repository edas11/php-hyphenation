<!doctype html>
<html>
<?php require('views/page-parts/head.php'); ?>
<body>
    <?php require('views/page-parts/main-header.php'); ?>
    <main>
        <h3>Patterns list</h3>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link page-previous">Previous</a>
                </li>
                <li class="page-item">
                    <a class="page-link page-previous"></a>
                </li>
                <li class="page-item active">
                    <a class="page-link page-current"></a>
                </li>
                <li class="page-item">
                    <a class="page-link page-next"></a>
                </li>
                <li class="page-item">
                    <a class="page-link page-next">Next</a>
                </li>
            </ul>
        </nav>

        <table class="table table-bordered ">
            <tr>
                <th>Nr.</th>
                <th>Pattern</th>
            </tr>
            <tbody id="patterns-table">

            </tbody>
        </table>
    </main>
    <script type="text/javascript" src="/static/main.js"/>
    <script type="text/javascript" >
    </script>
</body>
</html>