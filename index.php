<!DOCTYPE html>
<html lang="en">

<head>
  <title>The GUIDON API | Archives</title>

  <style>
*,
html,
body {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: black;
  padding: 40px;
  width: 100%;
  height: 100vh;

  display: flex;
  flex-flow: column nowrap;
  align-items: center;
  justify-content: center;
}

body img {
  width: 100%;
  max-width: 1000px;
  height: auto;
}
  </style>

  <?php wp_head(); ?>
</head>

<body>
  <img
    class="logo"
    src="<?php echo get_template_directory_uri() ?>/assets/logos/baseform-white.svg"
    alt="The GUIDON Archives"
  />
</body>

</html>