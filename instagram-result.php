<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./resources/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Instagram result</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <?php
          error_reporting(E_ERROR | E_PARSE);
          include('config.php');

          $apiKey = $config['API_KEY'];
          $pageID = $config['PAGE_ID'];
          $username = $_POST['search_username'];
          $url = "https://graph.facebook.com/v16.0/{$pageID}?fields=business_discovery.username({$username}){media{id,caption,comments_count,like_count,media_url,permalink,timestamp,username}}&access_token={$apiKey}";     
          $response = file_get_contents($url);

          if ($response) {
            $data = json_decode($response, true);

            ?>
              <div class="col-12 g-4">
                <h1 class="username text-center"><?php echo '@' . $username ?></h1>
              </div>
            <?php

            foreach ($data['business_discovery']['media']['data'] as $media) {
              ?>
                <div class="col-sm-6 col-lg-4 g-2">
                  <div class="card">    

                    <?php
                      if ($media['media_url'] != NULL) {
                        $url =  $media['media_url'];
                        $headers = get_headers($url, 1);
                        $contentType = $headers['Content-Type'];
                        if (strpos($contentType, 'image') !== false) {
                          ?>
                            <div class="instagram-post-image">
                              <img src="<?php echo $url ?>" alt="ig_post">
                            </div>
                          <?php
                        } elseif (strpos($contentType, 'video') !== false) {
                          ?> 
                            <div class="instagram-post-video">
                              <video src="<?php echo $url ?>" controls></video> 
                            </div>
                          <?php
                        } else {
                          ?> 
                            <p class="text-center">Unknown content!</p>
                          <?php
                        }
                      } else {
                        ?> 
                          <div class="instagram-post-reel text-center">
                            <a href="<?php echo $media['permalink']; ?>">It's reel!</a>
                          </div>
                        <?php
                      }
                    ?>

                    <div class="card-body">
                      <h5 class="display-inline float-start"><?php echo "Likes: " . $media['like_count']; ?></h5>
                      <h5 class="display-inline float-end"><?php echo "Comments: " . $media['comments_count']; ?></h5>
                      <p><?php echo substr($media['caption'], 0, 250) . '...'; ?></p>
                      <a href="<?php echo $media['permalink']; ?>">Check this post!</a>
                      <span class="float-end"><?php echo substr($media['timestamp'], 0, 10); ?></span>
                    </div>
                  </div>
                </div>      
              <?php
            }       
          } else {
            ?>
                <h1 class="text-center">There was an error sending the request!</h1>
            <?php
          }
        ?>
      </div>
    </div>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script> -->
  </body>
</html>