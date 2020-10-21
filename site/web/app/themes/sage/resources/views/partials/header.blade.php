<div class="wrapper-hero">
  <header class="header">
    <div class="container">
      <nav class="nav">
        @if (has_nav_menu('primary_navigation'))
          {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']) !!}
        @endif
      </nav>
    </div>
  </header>
  <div class="hero-text">
    <h1>
      {{ get_field('title') }}
    </h1>
    <p>
      {{ get_field('tagline') }}
    </p>
  </div>
</div>
