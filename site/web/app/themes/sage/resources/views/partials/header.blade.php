<header class="header">
  <div class="container">
    <nav class="nav">
			<li class="nav-item"><a href="#booking">Menu</a></li>
			@if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']) !!}
      @endif
    </nav>
  </div>
</header>
