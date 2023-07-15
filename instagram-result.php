<?php
  error_reporting(E_ERROR | E_PARSE);
  include('config.php');
  include('./resources/partials/header.html');
?>
   
<div class="container">
  <div class="row">
    <?php
      $apiKey = $config['API_KEY'];
      $pageID = $config['PAGE_ID'];
      $username = '';
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

<?php
  require('./resources/partials/footer.html');
?>