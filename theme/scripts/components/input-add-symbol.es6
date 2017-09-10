var Component = require('./component')

class InputAddSymbol extends Component {

	get listeners() {
		return {
			'click .form-add-symbol': 'handleClick'
		}
	}

	handleClick(e, self) {
		const $label =  $(e.currentTarget)
		const $input = $('#' + $label.attr('for'))
		$input.val($input.val() + $label.text());
	}
}

module.exports = InputAddSymbol


