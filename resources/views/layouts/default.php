<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODO List</title>
    <?= $this->assetManager->renderStyles() ?>
</head>
<body>
<?= $content ?? '' ?>
<?= $this->assetManager->renderScripts() ?>
</body>
</html>
