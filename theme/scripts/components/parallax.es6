var Component = require('./component')

class Parallax extends Component {

	constructor(element, data) {
		super(element, data)

		const $document = $(document)

		this.$container = $('#parallax-container')
		this.$title = $('#parallax-title')
		this.$image = $('#parallax-image')

		this.$window = $(window)
		this.$document = $(document)

		this.parallax()
	}

	get listeners() {
		return {
			'scroll': 'handleScroll'
		}
	}

	handleScroll(e, self) {
		self.parallax()
	}

	parallax() {
		const position = this.$window.scrollTop()
		this.$container.css('top', 0.01 * position + 'vh')
		this.$title.css('top', 0.02 * position + 'vh')
		this.$image.css('transform', 'translateY(' + 0.03 * position + 'vh)')
	}
}

module.exports = Parallax


