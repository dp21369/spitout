<i class="bi bi-search toggle-overlay"></i>
<aside>
<div class="align-items-center so-nav-search">
<div class="outer-close toggle-overlay">
    <!-- <a href='#!' class="headersearch-close"><span></span></a> -->
    <a href='#!' class="headersearch-close" aria-label="Close"></a>
  </div>
  <form class="search-form" action="<?php echo home_url( );?>">
    <input class="form-control mr-2" type="text" placeholder="Search..." name="s" value="<?php the_search_query(); ?>" aria-label="Search">
    <i class="bi bi-search"><input value="" type="submit" aria-label="Search"></i>
  </form>
</div>
</aside>
