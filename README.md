# TLAL Latest Posts Slider

A lightweight WordPress plugin that shows your latest blog posts as a single-slide, fading slider, in the style of the [Travelify](https://github.com/bmericc/travelify) theme's featured slider.

It is a few kilobytes of PHP, CSS and plain JavaScript. It does not load jQuery, a slider library or anything from a CDN.

Built for [Turkey Like A Local](https://www.turkeylikealocal.com/), which runs GeneratePress.

## Features

- One slide at a time with a fade transition. Autoplay runs every 5 seconds and pauses on hover, on focus and while the tab is hidden.
- The image fills the container at a 1018:460 ratio, which becomes 1:1 on mobile.
- Title and excerpt sit in a semi-transparent box at the bottom right. On mobile only the title shows, on a gradient.
- Dot pager, plus previous and next arrows that appear on hover. Touch swipe and the left and right arrow keys also work.
- Only posts with a featured image are used.
- Respects `prefers-reduced-motion`. Hidden slides are marked `inert` and `aria-hidden`.
- The first image loads eagerly with `fetchpriority="high"`. The others load lazily, and each one is preloaded before its turn.

## Installation

1. Copy the `tlal-latest-slider` folder into `wp-content/plugins/`, or clone this repository there.
2. Activate it under **Plugins**, or run `wp plugin activate tlal-latest-slider`.

## Usage

### Automatic (GeneratePress)

On the homepage (page 1 only), the slider is placed right below `<header>` via the `generate_after_header` hook. It shows the latest 5 posts, oldest to newest. It is skipped on AMP requests.

To change the homepage settings, use a filter:

```php
add_filter( 'tlal_slider_home_atts', function () {
	return [ 'count' => 6, 'order' => 'desc', 'category' => 'food-drink', 'autoplay' => 7000 ];
} );
```

To turn off the automatic placement:

```php
remove_action( 'generate_after_header', 'tlal_slider_home_output' );
```

### Shortcode

```
[tlal_slider count="5" category="istanbul" autoplay="5000" order="desc"]
```

| Attribute  | Default | Description |
|------------|---------|-------------|
| `count`    | `5`     | Number of posts (1–20) |
| `category` | —       | Category slug |
| `autoplay` | `5000`  | Delay in ms; `0` disables autoplay |
| `order`    | `desc`  | `desc` shows newest first. `asc` takes the latest N and shows them oldest to newest |

## Caching

If you use a page cache (WP Fastest Cache and similar), clear it after you install or update the plugin.

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
