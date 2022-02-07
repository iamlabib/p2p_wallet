<!DOCTYPE html>
<html lang="en">
<head>
  <title>P2P Wallet</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary container-fluid">
      <a class="navbar-brand text-white" href="#">P2P Wallet</a>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="nav navbar-nav menu">
          <li class="nav-item">
            <!-- <a class="nav-link active" href="#">Dashboard <span class="sr-only">(current)</span></a> -->
          </li>
        </ul>
      </div>
    </nav>
    @yield('content')  
    @stack('custom-js')
</body>
</html>

<!-- SELECT sent_amount FROM( SELECT  sent_amount FROM transactions ORDER BY sent_amount DESC LIMIT 3) AS A ORDER BY sent_amount LIMIT 1 -->
<!-- 
SELECT sender_id, sent_amount 
FROM
(SELECT sender_id, sent_amount FROM transactions group by sender_id DESC LIMIT 3) 
AS A 
ORDER BY sent_amount
LIMIT 1 -->