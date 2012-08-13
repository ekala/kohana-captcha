<?php defined('SYSPATH') OR die('No direct access.');
/**
 * Alpha captcha class.
 *
 * @package		Captcha
 * @subpackage	Captcha_Alpha
 * @author		Michael Lavers
 * @author		Kohana Team
 * @copyright	(c) 2008-2010 Kohana Team
 * @license		http://kohanaphp.com/license.html
 */
class Captcha_Alpha extends Captcha 
{
	/**
	 * Generates a new Captcha challenge.
	 *
	 * @return string The challenge answer
	 */
	public function generate_challenge()
	{
		// Complexity setting is used as character count
		$text = Text::random('distinct', max(1, Captcha::$config['complexity']));
		
		// Complexity setting is used as character count
		return $text;
	}

	/**
	 * Outputs the Captcha image.
	 *
	 * @param boolean $html Html output
	 * @return mixed
	 */
	public function render($html = TRUE)
	{
		// Creates $this->image
		$this->image_create(Captcha::$config['background']);

		// Calculate character font-size and spacing
		$default_size = min(Captcha::$config['width'], Captcha::$config['height'] * 2) / strlen($this->response);
		$spacing = (int) (Captcha::$config['width'] * 0.9 / strlen($this->response));

		// Background alphabetic character attributes
		$color_limit = mt_rand(96, 160);
		$chars = 'ABEFGJKLPQRTVY';

		list($r, $g, $b) = $this->hex2rgb(Captcha::$config['color']);
		// Draw each Captcha character with varying attributes
		for ($i = 0, $strlen = strlen($this->response); $i < $strlen; $i++)
		{
			// Use different fonts if available
			$font = Captcha::$config['fontpath'].Captcha::$config['fonts'][array_rand(Captcha::$config['fonts'])];

			$angle = mt_rand(-30, 20);
			// Scale the character size on image height
			$size = $default_size / 8 * mt_rand(12, 14);
			$box = imageftbbox($size, $angle, $font, $this->response[$i]);

			// Calculate character starting coordinates
			$x = $spacing / 4 + $i * $spacing;
			$y = Captcha::$config['height'] / 2 + ($box[2] - $box[5]) / 4;

			// Draw captcha text character
			$color = imagecolorallocate($this->image, $r, $g, $b);

			// Write text character to image
			imagefttext($this->image, $size, $angle, $x, $y, $color, $font, $this->response[$i]);
		}

		// Output
		return $this->image_render($html);
	}

} // End Captcha Alpha Driver Class