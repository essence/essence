<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Providers.
 */

class ProvidersTest extends TestCase {

	/**
	 *
	 */

	public $providers = [ ];



	/**
	 *
	 */

	public function setup( ) {

		$this->providers = json_decode(
			file_get_contents(
				dirname( dirname( dirname( __FILE__ )))
					. DIRECTORY_SEPARATOR . 'config'
					. DIRECTORY_SEPARATOR . 'providers.json'
			),
			true
		);
	}



	/**
	 *	@dataProvider providerProvider
	 */

	public function testProvider( $name, $url ) {

		$this->assertRegExp( $this->providers[ $name ]['filter'], $url );
	}



	/**
	 *
	 */

	public function providerProvider( ) {

		return [
			[ '23hq', 'http://www.23hq.com/Zzleeper/photo/16600737' ],
			//[ 'Animoto', '' ],
			[ 'Aol', 'http://on.aol.com/video/whats-next-for-google-in-two-minutes-518288612?icid=OnTechR3_Img' ],
			[ 'App.net', 'https://alpha.app.net/chrifpa/post/33532003/photo/1' ],
			[ 'Bambuser', 'http://bambuser.com/v/4740575' ],
			[ 'Bandcamp', 'http://jeanjean.bandcamp.com/track/coquin-l-l-phant' ],
			[ 'Blip.tv', 'http://blip.tv/nostalgiacritic/nostalgia-critic-blues-brothers-2000-6908864' ],
			[ 'Cacoo', 'http://cacoo.com/diagrams/00e77f4dc9973517' ],
			[ 'CanalPlus', 'http://www.canalplus.fr/c-divertissement/c-le-grand-journal/pid6831-connasse.html?vid=1067507' ],
			[ 'Chirb.it', 'http://chirb.it/7A9L9B' ],
			[ 'CircuitLab', 'https://www.circuitlab.com/circuit/53xa3r/digital-4-bit-counter-and-dac/' ],
			//[ 'Clikthrough', '' ],
			[ 'CollegeHumorOEmbed', 'http://www.collegehumor.com/video/6974337/gluten-free-duck' ],
			[ 'CollegeHumorOpenGraph', 'http://www.collegehumor.com/post/6977539/just-the-tip-15-pop-culture-tip-jars-deserving-of-your-dollar' ],
			[ 'Coub', 'http://coub.com/view/2a93c' ],
			[ 'Coub', 'http://coub.com/embed/2a93c' ],
			//[ 'CrowdRanking', '' ],
			[ 'DailyMile', 'http://www.dailymile.com/people/ben/entries/29297912' ],
			[ 'Dailymotion', 'http://www.dailymotion.com/video/x2091k1_pv-nova-comment-faire-un-tube-de-l-ete_fun' ],
			[ 'Deviantart', 'http://pachunka.deviantart.com/art/Cope-145564099' ],
			[ 'Dipity', 'http://www.dipity.com/multimediajournalism/30-days-that-destroyed-the-House-of-Murdoch/' ],
			[ 'Dotsub', 'http://dotsub.com/view/9c63db2a-fa95-4838-8e6e-13deafe47f09' ],
			[ 'Edocr', 'http://www.edocr.com/doc/176612/saint-petersburg-travellers-guide' ],
			[ 'Flickr', 'https://www.flickr.com/photos/loubella/14540822193/in/explore-2014-06-27' ],
			[ 'FunnyOrDie', 'http://www.funnyordie.com/videos/75d77b0795/hey-you' ],
			[ 'Gist', 'https://gist.github.com/felixgirault/5223712' ],
			[ 'Gmep', 'https://gmep.org/media/15162' ],
			[ 'HowCast', 'http://www.howcast.com/videos/512882-How-to-Make-an-Alabama-Slammer-Shots-Recipes' ],
			[ 'Huffduffer', 'http://huffduffer.com/peroty/167063' ],
			[ 'Hulu', 'http://www.hulu.com/watch/637586' ],
			[ 'Ifixit', 'http://www.ifixit.com/Teardown/iPhone-4-Teardown/3130/1' ],
			[ 'Ifttt', 'https://ifttt.com/recipes/98797-instagram-to-500px' ],
			[ 'Imgur', 'http://imgur.com/gallery/FEiFVeO' ],
			[ 'Instagram', 'http://instagram.com/p/pyHv4yvack/' ],
			[ 'Jest', 'http://www.jest.com/embed/209982/tbt-shoes' ],
			[ 'Justin.tv', 'http://www.justin.tv/deepellumonair' ],
			[ 'Kickstarter', 'https://www.kickstarter.com/projects/1452363698/good-seed-craft-veggie-burgers' ],
			[ 'Meetup', 'http://www.meetup.com/France-HTML5-User-Group/' ],
			[ 'Mixcloud', 'http://www.mixcloud.com/upanddown/the-jazz-pit-vol-3-dublin-diggin/' ],
			[ 'Mobypicture', 'http://www.mobypicture.com/user/mathys/view/242008' ],
			[ 'Nfb', 'https://www.nfb.ca/film/performer?hpen=feature_5' ],
			[ 'Official.fm', 'http://www.official.fm/playlists/U2CP' ],
			[ 'Polldaddy', 'http://polldaddy.com/poll/7012505/' ],
			//[ 'PollEverywhere', '' ],
			[ 'Prezi', 'http://prezi.com/cu7u5qponn75/embed-prezi-in-wordpress-html/' ],
			//[ 'Qik', '' ],
			[ 'Rdio', 'https://www.rdio.com/artist/Soundgarden/album/Superunknown/' ],
			//[ 'Revision3', 'http://revision3.com/anyhoo/5-fun-facts-about-wimbledon-that-are-aces/' ],
			//[ 'Roomshare', '' ],
			[ 'Sapo', 'http://videos.sapo.pt/lqaaVwaC1u3mucwA9aTf' ],
			[ 'Screenr', 'http://www.screenr.com/A7ks' ],
			//[ 'Scribd', '' ],
			[ 'Shoudio', 'https://shoudio.com/user/sowa/status/13420' ],
			[ 'Sketchfab', 'https://sketchfab.com/models/qsRPEw7hTKC4E02XMop9DUpu2wb' ],
			[ 'SlideShare', 'http://www.slideshare.net/bridoo/revisiting-theoldrulesofbranding' ],
			[ 'SoundCloud', 'https://soundcloud.com/general-levy/new-general-levy-catchy-record' ],
			[ 'SpeakerDeck', 'https://speakerdeck.com/shpigford/4-signs-your-business-is-dying' ],
			//[ 'Spotify', '' ],
			[ 'TedOEmbed', 'https://www.ted.com/talks/jane_mcgonigal_the_game_that_can_give_you_10_extra_years_of_life' ],
			[ 'TedOpenGraph', 'https://www.ted.com/talks/kelly_mcgonigal_how_to_make_stress_your_friend' ],
			[ 'Twitter', 'https://twitter.com/ouatchfr/status/478231342942265344' ],
			[ 'Ustream', 'http://www.ustream.tv/channel/red-shoes-billiards-60803-camera-1' ],
			//[ 'Vhx', '' ],
			[ 'Viddler', 'http://www.viddler.com/v/bdce8c7' ],
			//[ 'Videojug', '' ],
			[ 'Vimeo', 'http://vimeo.com/channels/staffpicks/69840759' ],
			[ 'Vine', 'https://vine.co/v/MdnPb5ivU52' ],
			[ 'WordPress', 'http://developer.wordpress.com/docs/oembed-provider-api/' ],
			//[ 'Yfrog', '' ],
			[ 'Youtube', 'https://www.youtube.com/watch?v=DXh6HPXvNMs' ]
		];
	}
}
