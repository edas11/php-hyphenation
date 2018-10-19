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
        <h3>Hyphenate words</h3>

        <form id="post-form">
            <div class="form-group">
                <label for="wordsTohyphenate">Enter word to hyphenate (seperated by spaces)</label>
                <input type="text" name="words" class="form-control" id="wordsTohyphenate" placeholder="Enter word">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <?php if (array_key_exists('skippedWords', $data) && count($data['skippedWords']) > 0) { ?>
        <h4>Skipped:</h4>
        <table class="table table-bordered">
            <tr>
                <th>Original word</th>
                <th>Hyphenated word</th>
            </tr>
            <?php foreach ($data['skippedWords'] as $word => $hyphenatedWord) { ?>
                <tr>
                    <td><?= $word ?></td>
                    <td><?= $hyphenatedWord ?></td>
                </tr>
            <?php } ?>
        </table>
        <?php } ?>

        <?php if (array_key_exists('hyphenatedWords', $data) && count($data['hyphenatedWords']) > 0) { ?>
        <h4>Hyphenated:</h4>
        <table class="table table-bordered">
            <tr>
                <th>Original word</th>
                <th>Hyphenated word</th>
            </tr>
            <?php foreach ($data['hyphenatedWords'] as $word => $hyphenatedWord) { ?>
                <tr>
                    <td><?= $word ?></td>
                    <td><?= $hyphenatedWord ?></td>
                </tr>
            <?php } ?>
        </table>
        <?php } ?>
    </main>
    <script type="text/javascript" src="/static/main.js"/>
    <script type="text/javascript"></script>
</body>
</html>