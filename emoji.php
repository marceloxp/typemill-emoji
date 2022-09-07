<?php
namespace Plugins\emoji;

use \Typemill\Plugin;

class Emoji extends Plugin
{
  # <span class="ec ec-tada"></span>
  # [:emoji id="tada":]
  protected $settings;

  # listen to the shortcode event
  public static function getSubscribedEvents()
  {
    return array(
      'onShortcodeFound' => 'onShortcodeFound',
      'onTwigLoaded' => 'onTwigLoaded',
    );
  }

  public function onTwigLoaded()
  {
    $this->addCSS('/emoji/public/css/style.css');
  }

  # if typemill found a shortcode and fires the event
  public function onShortcodeFound($shortcode)
  {
    # read the data of the shortcode
    $shortcodeArray = $shortcode->getData();

    if(is_array($shortcodeArray) && $shortcodeArray['name'] == 'registershortcode')
    {
        $shortcode->setData($shortcodeArray);
    }

    // var_dump($shortcodeArray);
    # check if it is the shortcode name that we where looking for
    if(is_array($shortcodeArray) && $shortcodeArray['name'] == 'emoji')
    {
      try {
        # we found our shortcode, so stop firing the event to other plugins
        $shortcode->stopPropagation();

        $params = $shortcodeArray['params'];
        $id = isset($params['id']) ? $params['id'] : 'tada';

        $html = '<span class="ec ec-' . $id . '"></span>';

        # and return a html-snippet that replaces the shortcode on the page.
        $shortcode->setData($html);
      } catch (\Throwable $th) {
        throw $th;
      }
    }
  }

}
