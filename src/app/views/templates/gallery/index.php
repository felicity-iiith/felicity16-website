<?php $this->load_fragment('skeleton_template/header', ['title' => __('Gallery')]); ?>
<article class="page gallery">
  <header>
      <h1>Gallery</h1>
  </header>
  <div class="thumbnail-wrapper">
      <div class="col col1"></div>
      <div class="col col2"></div>
  </div>

  <div class="large-image"></div>

  <script type="text/javascript">
  (function () {
      var galleryImages = [
          "6.jpg","HairPainting.jpg","old_87.jpg","old_85.jpg","old_84.jpg",
          "old_83.jpg","old_79.jpg","old_74.jpg","old_55.jpg","old_53.jpg",
          "old_41.jpg","old_40.jpg","old_39.jpg","old_38.jpg","old_35.jpg",
          "old_34.jpg","old_30.jpg","IMG_2286.jpg","IMG_0263.jpg","IMG_0227.jpg",
          "IMG_0132.jpg","9.jpg","8.jpg","7.jpg","5.jpg","4.jpg","old_88.jpg",
          "3.jpg","22.jpg","21.jpg","20.jpg","2.jpg","19.jpg","18.jpg","17.jpg",
          "16.jpg","15.jpg","14.jpg","13.jpg","12.jpg","11.jpg","10.jpg","1.jpg"
      ],
      col1H = 0,
      col2H = 0,
      $col1 = $('.col1'),
      $col2 = $('.col2'),
      $largeImg = $('.large-image'),
      $window = $(window);

      // (function (o) {
      // 	for(var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
      // })(galleryImages);

      var thumbOnClick = function (e) {
          var $img = $largeImg.children();
          if ( $img.length === 0 ) {
              $largeImg.append( $('<img>').attr( 'src', this.src.replace('gallery_thumbs', 'gallery')) );
          } else {
              $img.attr( 'src', this.src.replace('gallery_thumbs', 'gallery'));
          }
      };

      var getImage = function(i, imageName) {
          var I = new Image();
          I.src = baseUrl + 'static/images/gallery_thumbs/' + imageName;
          I.alt = 'gallery thumbnail';

          I.onload = placeThumb;
      };

      var placeThumb = function () {
          if ( $largeImg.children().length === 0 ) {
              $largeImg.append( $('<img>').attr( 'src', this.src.replace('gallery_thumbs', 'gallery')) );
          }
          if (col1H <= col2H) {
              col1H += $col1.append( $(this).addClass('thumbnail').click(thumbOnClick) ).height();
          } else {
              col2H += $col2.append( $(this).addClass('thumbnail').click(thumbOnClick) ).height();
          }
      };
      if (!$($window).data('gallery-scroll-handler')) {
          $window.data('gallery-scroll-handler', true);
          $window.on('scroll', function () {
              $largeImg.css('top', Math.max(0, $('.gallery').offset().top - $(document).scrollTop()));
          });
      }
      $.each(galleryImages, getImage);
  })();
  </script>
</article>
<?php $this->load_fragment('skeleton_template/footer'); ?>
