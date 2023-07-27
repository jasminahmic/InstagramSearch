<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./resources/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&family=Roboto&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
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
          $limit = $_POST['search_number'];
          $url = "https://graph.facebook.com/v16.0/{$pageID}?fields=business_discovery.username({$username}){media.limit($limit){id,caption,comments_count,like_count,media_url,permalink,timestamp,username}}&access_token={$apiKey}";     
          $response = file_get_contents($url);

          if ($response) {
            $data = json_decode($response, true);

            ?>
              <div class="col-12 g-4">
                <div class="username d-flex justify-content-between align-items-center">
                  <a href="https://instagram.com" class="m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                      <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                    </svg>
                  </a>
                  <h1 class="d-inline m-0"><?php echo '@' . $username ?></h1>
                  <a class="m-0" href="index.html">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                      <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                  </a>
                </div>
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
                      <h6 class="display-inline float-start"><?php echo "Likes: " . $media['like_count']; ?></h6>
                      <h6 class="display-inline float-end"><?php echo "Comments: " . $media['comments_count']; ?></h6>
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
  </body>
</html>