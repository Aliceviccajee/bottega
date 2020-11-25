<header class="header">
  <div class="container">
		<div class="logo">
			<a href="https://bottegapizza.co.uk/">B</a>
		</div>
    <nav class="nav">
			@if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']) !!}
      @endif
    </nav>
  </div>
</header>
