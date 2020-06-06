<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>metronic | 后台管理模板</title>
    <style media="screen">
      .{
        padding: 0;
        margin: 0;
      }
      a{
        display: block;
        color: black;
        text-decoration: none;
        font-family: Micrsoft;
        background: #ccc;
        line-height: 40px;
        padding-left: 30px;
        margin-top: 5px;
      }
      a:hover{
        color: red;
        font-size: 30px;
      }
      .myclass {
        padding: 0;
        margin: 0 auto;
        /*text-align: center;*/
        width: 700px;
      }
    </style>
  </head>
  <body>

    <div class="myclass">
      <h3>metronic | 模板目录</h3>
      <?php
        $hostdir = dirname(__FILE__);
        $filenames = scandir($hostdir);
        $i=0;
        foreach ($filenames as $key => $v) {
          echo "<div><a href='$v' target='_blank'>$i.$v</a></div>";
          $i++;
        }
       ?>
    </div>

  </body>
</html>
