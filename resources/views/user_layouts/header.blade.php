<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Blog</title>
    <link rel="stylesheet" href="{{ asset('frontend/style.css') }}" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
    <!-- material icon -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/material-icons@1.13.11/iconfont/material-icons.min.css"
    />
    <!-- Box Icon -->
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>
  <body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
  // csrf token 
  // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  // console.log(CSRF_TOKEN)
</script>