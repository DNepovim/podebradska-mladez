var Component = require('./component')

class Parallax extends Component {

	constructor(element, data) {
		super(element, data)

		const $document = $(document)

		this.parallax()

		$document.on('scroll', () => {
			this.parallax()
		})
	}

	parallax() {
		const $slider = $('#parallax')
		const $window = $(window)
		const $document = $(document)
		let position = -20 * $window.scrollTop() / ($document.height() - $window.height())
		$slider.css('top', position + 'vh')
	}
}

module.exports = Parallax


