<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function css_version() {
      return 1.0000;
}
function js_version() {
      return 1.0000;
}
function img_version() {
      return 1.0000;
}
/**
 * Append url to base_url(assets/uploads)
 * @param string $url
 */
function a_base_url($url = '') {
      return base_url('assets/uploads/' . $url);
}
/**
 * Append url to base_url(admin)
 * @param string $url
 */
function ad_base_url($url = '') {
      return base_url('admin/' . $url);
}

/**
 * Append url to base_url(assets)
 * @param string $url
 */
function as_base_url($url = '') {
      return base_url('assets/' . $url);
}