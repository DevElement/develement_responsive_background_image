# develement_responsive_background_image
Fluid view helper to render responsive background images

## Example usage
```
{namespace d=DevElement\DevelementResponsiveBackgroundImage\ViewHelper}
<d:responsiveBackgroundImage image="{files.0}" id="banner-{uid}">
    <div class="banner" id="banner-{uid}"></div>
</d:responsiveBackgroundImage>
```

## Breakpoints

By default, breakpoints for bootstrap are configured:
- lg: image will get resizes to ``1920px``
- md: (> 992px and < 1199px), image will get resized to ``1199px``
- sm: (> 768px and < 991px), image will get resized to ``991px``
- xs: (> 480px and < 767px), image will get resized to ``767px``
- < xs: (< 479px), image will get resized to ``479px`` (this resolution isn't a bootstrap break point, but is required for Google PageSpeed)

## Installation
Add the following to the ``repositories`` block in your composer.json:
```
{
  "type": "vcs",
  "url": "https://github.com/DevElement/develement_responsive_background_image.git"
}
```    

And run ``composer require develement/develement-responsive-background-image dev-master``.

Make sure ``EXT:develement_responsive_background_image/Resources/Public/JavaScript/bg-responsive.js`` is included.
