<!DOCTYPE html>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->Title ?></title>
    <base href="<?php echo BASE_URL ?>">

    <?php foreach ($this->getStyles() as $style) : ?>
        <link href="<?php echo $style ?>" type="text/css" rel="stylesheet">
    <?php endforeach; ?>

    <?php foreach ($this->getHeaderScripts() as $script) : ?>
        <script src="<?php echo $script ?>"></script>
    <?php endforeach; ?>

</head>

<body>

    <?php $this->addTemplate("header"); ?>
    <?php $this->addTemplate("content"); ?>
    <?php $this->addTemplate("footer"); ?>

    <?php foreach ($this->getFooterScripts() as $script) : ?>
        <script src="<?php echo $script ?>"></script>
    <?php endforeach; ?>

</body>
</html>
