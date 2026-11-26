=== TLAL Latest Posts Slider ===
Contributors: bmericc
Tags: slider, latest posts, carousel, featured image, generatepress
Requires at least: 6.3
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 2.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightweight single-slide fade slider of your latest posts. No jQuery, no slider library, no CDN.

== Description ==

TLAL Latest Posts Slider shows your latest posts as a full-width slider with one slide at a time and a fade transition, in the style of the classic Travelify theme's featured slider.

The plugin is a few kilobytes of PHP, CSS and plain JavaScript. It does not load jQuery, a slider library or any file from a third-party server.

**Features**

* One slide at a time with a fade transition. Autoplay pauses on hover, on focus and while the browser tab is hidden.
* The image fills the container at a 1018:460 ratio, which becomes square on mobile.
* Title and excerpt sit in a semi-transparent box. On mobile only the title shows, on a gradient.
* Dot pager, plus previous and next arrows. Touch swipe and the left and right arrow keys also work.
* Only posts with a featured image are used.
* Respects "reduce motion". Hidden slides are marked `inert` and `aria-hidden`.
* The first image loads with high priority and the rest lazily. Each image is preloaded before its turn.
* On GeneratePress, the slider is added automatically below the header on the homepage. It works with any theme through the shortcode.

**Shortcode**

`[tlal_slider count="5" category="istanbul" autoplay="5000" order="desc"]`

* `count`: number of posts, 1 to 20. Default `5`.
* `category`: category slug. Default: all categories.
* `autoplay`: delay in milliseconds. `0` turns autoplay off. Default `5000`.
* `order`: `desc` shows the newest first. `asc` takes the latest posts and shows them oldest to newest. Default `desc`.

**Homepage placement (GeneratePress)**

On the first page of the homepage, the slider is printed right after `<header>` through the `generate_after_header` hook, showing the latest 5 posts oldest to newest. It is skipped on AMP requests.

Change the homepage settings with a filter:

`add_filter( 'tlal_slider_home_atts', function () { return [ 'count' => 6, 'order' => 'desc' ]; } );`

Turn the automatic placement off:

`remove_action( 'generate_after_header', 'tlal_slider_home_output' );`

Development happens on [GitHub](https://github.com/bmericc/tlal-latest-slider).

== Installation ==

1. Upload the `tlal-latest-slider` folder to `/wp-content/plugins/`, or install it from the Plugins screen.
2. Activate the plugin from the **Plugins** screen.
3. On GeneratePress, the slider appears on the homepage automatically. With other themes, add the `[tlal_slider]` shortcode where you want it.
4. If you use a page cache plugin, clear the cache.

== Frequently Asked Questions ==

= Does it work without GeneratePress? =

Yes. Automatic placement only happens on GeneratePress. With any other theme, use the `[tlal_slider]` shortcode in a page, a post or a Shortcode block.

= Why are some of my posts missing? =

Only posts with a featured image are shown. Set a featured image on the post and it will appear.

= Does it load external files? =

No. Everything is served from your own site.

= Can I style it? =

Yes. All classes start with `tlal-` (for example `.tlal-slider`, `.tlal-slide__text`, `.tlal-slider__dot`). Override them in your theme or in the Customizer's Additional CSS.

== Screenshots ==

1. The slider on the homepage, right below the header.

== Changelog ==

= 2.0.2 =
* Added readme.txt and WordPress.org plugin header fields.
* Text domain is now `tlal-latest-slider`, matching the plugin slug.

= 2.0.1 =
* The homepage output is now a named function, so it can be removed with `remove_action()`.

= 2.0.0 =
* Rewritten as a single-slide fade slider in the style of the Travelify featured slider.
* Only posts with a featured image are used.
* Added a dot pager, touch swipe and keyboard navigation.

= 1.1.0 =
* Added the `order` attribute. The homepage shows the latest 5 posts oldest to newest.

= 1.0.0 =
* First release: a three-card carousel of the latest posts.

== Upgrade Notice ==

= 2.0.2 =
Readme and plugin header updates only. No change in behaviour.
