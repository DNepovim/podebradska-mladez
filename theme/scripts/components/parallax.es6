var Component = require('./component')

class Parallax extends Component {

	constructor(element, data) {
		super(element, data)

		$(document).on('scroll', () => {
			this.parallax()
		})


	}

	parallax() {
		var $slider = $('#parallax')

		// scroll amount / parallax speed
		var yPos = -($(window).scrollTop() / $($slider).data('speed'))

		// move background image
		$($slider).find('img').css('object-position', 'center ' + yPos + 'px')
	}
}

module.exports = Parallax


