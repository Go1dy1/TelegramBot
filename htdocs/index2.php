<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<div id='response'></div>
<form method="POST" enctype="multipart/form-data" name="ourForm">

    <div class="mb-3">
        <label for="file" class="form-label">File</label>
        <input id="file" type="file" name="file" class="form-control"/>
    </div>

    <div class="mb-3">
        <label for="message" class="form-label">Message</label> <br>
        <textarea id="message" name="message" class="form-control"></textarea>
    </div>
    <button type="submit" name="submit-button" class="form-control">Submit</button>
</form>
<script src='Ajax.js' > </script>
</body>
</html>
