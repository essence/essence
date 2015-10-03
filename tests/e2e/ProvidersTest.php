<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
use PHPUnit_Framework_TestCase as TestCase;
use Essence\Essence;



/**
 *	Test case for Providers.
 */
class ProvidersTest extends TestCase {

	/**
	 *
	 */
	public $Essence = null;



	/**
	 *
	 */
	public function setUp() {
		$this->Essence = new Essence();
	}



	/**
	 *	Tests essence on real providers.
	 *
	 *	@large
	 *	@dataProvider providerProvider
	 *	@param $name Provider name.
	 *	@param $url URL to extract.
	 *	@param $property Name of the property to test.
	 *	@param $expected Property expected.
	 */
	public function testExtract($name, $url, $property, $expected) {
		$Media = $this->Essence->extract($url);

		if (!$Media) {
			return $this->markTestSkipped(
				"Unable to extract info from '$url'"
			);
		}

		$value = $Media->get($property);

		if ($value != $expected) {
			return $this->markTestSkipped(
				"The value of '$property' ('$value') "
				. "is not as expected ('$expected') "
				. "when trying to extract info from '$url'"
			);
		}

		$this->assertEquals($expected, $value);
	}



	/**
	 *
	 */
	public function providerProvider() {
		return [
			[
				'23hq',
				'http://www.23hq.com/Zzleeper/photo/16600737',
				'authorName',
				'Zzleeper'
			],
			/*[
				'Animoto',
				'',
				'',
				''
			],*/
			/*[
				'Aol',
				'http://on.aol.com/video/whats-next-for-google-in-two-minutes-518288612?icid=OnTechR3_Img',
				'',
				''
			],*/
			[
				'App.net',
				'https://alpha.app.net/chrifpa/post/33532003/photo/1',
				'authorName',
				'@chrifpa'
			],
			[
				'Bambuser',
				'http://bambuser.com/v/4740575',
				'authorName',
				'dancole'
			],
			[
				'Bandcamp',
				'http://jeanjean.bandcamp.com/track/coquin-l-l-phant',
				'providerName',
				'bandcamp'
			],
			/*[
				'Cacoo',
				'http://cacoo.com/diagrams/00e77f4dc9973517',
				'',
				''
			],*/
			[
				'CanalPlus',
				'http://www.canalplus.fr/c-divertissement/c-le-grand-journal/pid6831-connasse.html?vid=1067507',
				'providerName',
				'Canalplus.fr'
			],
			[
				'Chirb.it',
				'http://chirb.it/7A9L9B',
				'authorName',
				'nvanderklippe'
			],
			[
				'CircuitLab',
				'https://www.circuitlab.com/circuit/53xa3r/digital-4-bit-counter-and-dac/',
				'authorName',
				'CircuitLab'
			],
			/*[
				'Clikthrough',
				'',
				'',
				''
			],*/
			[
				'CollegeHumorOEmbed',
				'http://www.collegehumor.com/video/6974337/gluten-free-duck',
				'authorName',
				'CollegeHumor'
			],
			[
				'CollegeHumorOpenGraph',
				'http://www.collegehumor.com/post/6977539/just-the-tip-15-pop-culture-tip-jars-deserving-of-your-dollar',
				'og:site_name',
				'CollegeHumor'
			],
			[
				'Coub',
				'http://coub.com/view/2a93c',
				'authorName',
				'Marat Akamov'
			],
			[
				'Coub',
				'http://coub.com/embed/2a93c',
				'authorName',
				'Marat Akamov'
			],
			/*[
				'CrowdRanking',
				'',
				'',
				''
			],*/
			[
				'DailyMile',
				'http://www.dailymile.com/people/ben/entries/29297912',
				'authorName',
				'Ben W.'
			],
			[
				'Dailymotion',
				'http://www.dailymotion.com/video/x2091k1_pv-nova-comment-faire-un-tube-de-l-ete_fun',
				'authorName',
				'Golden Moustache'
			],
			[
				'Dai.ly',
				'http://dai.ly/x2091k1',
				'authorName',
				'Golden Moustache'
			],
			[
				'Deviantart',
				'http://pachunka.deviantart.com/art/Cope-145564099',
				'authorName',
				'Pachunka'
			],
			[
				'Dipity',
				'http://www.dipity.com/multimediajournalism/30-days-that-destroyed-the-House-of-Murdoch/',
				'authorName',
				'multimediajournalism'
			],
			[
				'Dotsub',
				'http://dotsub.com/view/9c63db2a-fa95-4838-8e6e-13deafe47f09',
				'authorName',
				'liuxt'
			],
			[
				'Edocr',
				'http://www.edocr.com/doc/176612/saint-petersburg-travellers-guide',
				'authorName',
				'info_769'
			],
			[
				'Flickr',
				'https://www.flickr.com/photos/loubella/14540822193/in/explore-2014-06-27',
				'authorName',
				'Lou-bella'
			],
			[
				'FunnyOrDie',
				'http://www.funnyordie.com/videos/75d77b0795/hey-you',
				'title',
				'Hey You'
			],
			/*[
				'Gist',
				'https://gist.github.com/felixgirault/5223712',
				'',
				''
			],*/
			/*[
				'Gmep',
				'https://gmep.org/media/15162',
				'',
				''
			],*/
			[
				'HowCast',
				'http://www.howcast.com/videos/512882-How-to-Make-an-Alabama-Slammer-Shots-Recipes',
				'title',
				'How to Make an Alabama Slammer | Howcast'
			],
			[
				'Huffduffer',
				'http://huffduffer.com/peroty/167063',
				'authorName',
				'peroty'
			],
			[
				'Hulu',
				'http://www.hulu.com/watch/637586',
				'authorName',
				'FOX'
			],
			[
				'Ifixit',
				'http://www.ifixit.com/Teardown/iPhone-4-Teardown/3130/1',
				'title',
				'iPhone 4 Teardown'
			],
			[
				'Ifttt',
				'https://ifttt.com/recipes/98797-instagram-to-500px',
				'title',
				'Instagram to 500px'
			],
			[
				'Imgur',
				'http://imgur.com/gallery/FEiFVeO',
				'providerName',
				'Imgur'
			],
			[
				'Instagram',
				'http://instagram.com/p/pyHv4yvack/',
				'authorName',
				'lekemar'
			],
			/*[
				'Jest',
				'http://www.jest.com/embed/209982/tbt-shoes',
				'',
				''
			],*/
			/*[
				'Justin.tv',
				'http://www.justin.tv/deepellumonair',
				'',
				''
			],*/
			[
				'Kickstarter',
				'https://www.kickstarter.com/projects/1452363698/good-seed-craft-veggie-burgers',
				'authorName',
				'Oliver Ponce and Erin Shotwell'
			],
			[
				'Meetup',
				'http://www.meetup.com/France-HTML5-User-Group/',
				'title',
				'France HTML5 User Group'
			],
			[
				'Mixcloud',
				'http://www.mixcloud.com/upanddown/the-jazz-pit-vol-3-dublin-diggin/',
				'authorName',
				'The Jazz Pit'
			],
			[
				'Mobypicture',
				'http://www.mobypicture.com/user/mathys/view/242008',
				'authorName',
				'Mathys van Abbe'
			],
			/*[
				'Nfb',
				'https://www.nfb.ca/film/performer?hpen=feature_5',
				'',
				''
			],*/
			[
				'Official.fm',
				'http://www.official.fm/playlists/U2CP',
				'authorName',
				'Cameo Gallery'
			],
			[
				'Polldaddy',
				'http://polldaddy.com/poll/7012505/',
				'title',
				'Which design do you prefer?'
			],
			/*[
				'PollEverywhere',
				'',
				'',
				''
			],*/
			[
				'Prezi',
				'http://prezi.com/cu7u5qponn75/embed-prezi-in-wordpress-html/',
				'title',
				'Embed Prezi in WordPress HTML'
			],
			/*[
				'Qik',
				'',
				'',
				''
			],*/
			[
				'Rdio',
				'https://www.rdio.com/artist/Soundgarden/album/Superunknown/',
				'title',
				'Superunknown'
			],
			/*[
				'Revision3',
				'http://revision3.com/anyhoo/5-fun-facts-about-wimbledon-that-are-aces/',
				'',
				''
			],*/
			/*[
				'Roomshare',
				'',
				'',
				''
			],*/
			/*[
				'Sapo',
				'http://videos.sapo.pt/lqaaVwaC1u3mucwA9aTf',
				'',
				''
			],*/
			[
				'Screenr',
				'http://www.screenr.com/A7ks',
				'authorName',
				'elearning'
			],
			/*[
				'Scribd',
				'',
				'',
				''
			],*/
			[
				'Shoudio',
				'https://shoudio.com/user/sowa/status/13420',
				'providerName',
				'Shoudio, the location based audio platform'
			],
			[
				'Sketchfab',
				'https://sketchfab.com/models/qsRPEw7hTKC4E02XMop9DUpu2wb',
				'authorName',
				'Virtual Studio'
			],
			[
				'SlideShare',
				'http://www.slideshare.net/bridoo/revisiting-theoldrulesofbranding',
				'authorName',
				'Bridget Jung'
			],
			[
				'SoundCloud',
				'https://soundcloud.com/general-levy/new-general-levy-catchy-record',
				'authorName',
				'General levy'
			],
			[
				'SpeakerDeck',
				'https://speakerdeck.com/shpigford/4-signs-your-business-is-dying',
				'authorName',
				'Josh Pigford'
			],
			/*[
				'Spotify',
				'',
				'',
				''
			],*/
			[
				'TedOEmbed',
				'https://www.ted.com/talks/jane_mcgonigal_the_game_that_can_give_you_10_extra_years_of_life',
				'authorName',
				'Jane McGonigal'
			],
			/*[
				'TedOpenGraph',
				'https://www.ted.com/talks/kelly_mcgonigal_how_to_make_stress_your_friend',
				'',
				''
			],*/
			[
				'Twitter',
				'https://twitter.com/ouatchfr/status/478231342942265344',
				'authorName',
				'Ouatch.fr'
			],
			[
				'Ustream',
				'http://www.ustream.tv/channel/red-shoes-billiards-60803-camera-1',
				'authorName',
				'redsh0es'
			],
			/*[
				'Vhx',
				'',
				'',
				''
			],*/
			[
				'Viddler',
				'http://www.viddler.com/v/bdce8c7',
				'title',
				'Viddler Platform Overview'
			],
			/*[
				'Videojug',
				'',
				'',
				''
			],*/
			[
				'Vimeo',
				'http://vimeo.com/channels/staffpicks/69840759',
				'authorName',
				'WhenYouBrokeMyheartTeam'
			],
			[
				'Vine',
				'https://vine.co/v/MdnPb5ivU52',
				'description',
				'Vine by Renee Derr'
			],
			[
				'WordPress',
				'http://developer.wordpress.com/docs/oembed-provider-api/',
				'authorName',
				'Justin Shreve'
			],
			/*[
				'Yfrog',
				'',
				'',
				''
			],*/
			[
				'Youtube',
				'https://www.youtube.com/watch?v=DXh6HPXvNMs',
				'authorName',
				'Golden Moustache'
			],
            [
				'Youtube embedded',
				'https://www.youtube.com/embed/DXh6HPXvNMs',
				'authorName',
				'Golden Moustache'
			],
		];
	}
}
