<?php 
  $product_api = file_get_contents("https://spandiv.xyz/wp-json/spandiv/v1/random-product/");
  $product = json_decode($product_api, true);
?>

<div class="col-lg-3 d-block">
  <div class="card border-0 shadow-sm sticky-top" style="position: sticky;top:3rem">
    <a href="<?= $product['permalink'] ?>" target="_blank">
      <img class="card-img-top" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="background-image: url('<?= $product['thumbnail'] ?>'); background-repeat: no-repeat; height: 200px; width: 100%; background-size:cover; border-radius: .3rem .3rem 0 0;">
    </a>
    <div class="card-body pb-0 bg-white">
        <div class="row">
          <div class="col text-start">
              <p class="mb-0 fw-bold text-truncate"><?= $product['post_title'] ?></p>
      			  <span>
      				  <?php foreach($product['category'] as $category) : ?>
      				  <span class="badge bg-primary"><?= $category['name'] ?></span>
      				  <?php endforeach ?>
      			  </span>
          </div>
        </div>
        <hr>
        <p><?= $product["post_excerpt"] ?></p>
    </div>
    <div class="card-footer border-0 bg-white text-center pb-3">
        <a class="btn btn-primary rounded-3 d-block" href="<?= $product['permalink']?>" target="_blank" rel="bookmark"><span class="dashicons dashicons-external"></span> Lihat Lebih Lanjut</a>
    </div>
  </div>
</div>
