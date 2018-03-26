<!DOCTYPE html>
<html lang="en">
<head>
    <?php foreach ($this->getStyles() as $style) : ?>
    <link href="<?= $style ?>" type="text/css" rel="stylesheet">
    <?php endforeach; ?>

    <?php foreach ($this->getHeaderScripts() as $style) : ?>
    <script src="<?= $style ?>"></script>
    <?php endforeach; ?>
</head>
<body>
    <?php require_once APP_DIR . "templates/default/sidebar.php"; ?>
    <?php require_once APP_DIR . "templates/default/topbar.php"; ?>
    <?php require_once APP_DIR . "templates/default/content.php"; ?>
    <?php require_once APP_DIR . "templates/default/footer.php"; ?>

    <?php foreach ($this->getFooterScripts() as $style) : ?>
        <script src="<?= $style ?>"></script>
    <?php endforeach; ?>
</body>
</html>
