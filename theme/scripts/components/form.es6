var Component = require('./component')

class Form extends Component {
	constructor(element, data) {
		super(element, data)

		this.valid = {}
		this.$formInputs = this.$el.find('input:not([type=hidden]):not([type=submit])')
		this.$formInputs.each( (i, v) => {
			let rules = $(v).data('nette-rules')
			this.valid[$(v).attr('name')] = {}
			$.each(rules, (o, rule) => {
				this.valid[$(v).attr('name')][rule.op] = false
			})
		})
	}

	get listeners() {
		return {
			'focusout input': 'handleVerified',
			'keydown input': 'handleChange',
			'click button': 'handleSubmit',
			'click .form-loader': 'handleHideMessage'
		}
	}

	handleVerified(e, self) {
		const $input = $(e.currentTarget)
		const value = $input.val()
		const rules = $input.data('nette-rules')
		const $messageField = $('[data-for="' + $input.attr('id') + '"]')
		const testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i
		$.each(rules, (i, v) => {
			if (v.op == ':filled' && !value ) {
				$messageField.text(v.msg)
				self.valid[$input.attr('name')][':filled'] = false
			} else if (v.op == ':email' && !testEmail.test(value) ) {
				$messageField.text(v.msg)
				self.valid[$input.attr('name')][':email'] = false
			} else {
				$messageField.text('')
				$.each(self.valid[$input.attr('name')], (k, v) => {
					self.valid[$input.attr('name')][k] = true
				})
			}
		})
	}

	handleChange(e, self) {
		const $input = $(e.currentTarget)
		const $messageField = $('[data-for="' + $input.attr('id') + '"]')
		$messageField.text('')
	}

	handleSubmit(e, self) {
		e.preventDefault()
		self.$el.addClass('is-in-process')
		let valid = true
		let errorMessages = []
		$.each(self.valid, (k, v) => {
			$.each(v, (rule, value) => {
				let rules = self.$el.find('#frm-' + k).data('nette-rules')
				if (!value) {
					$.each(rules, (i, ruleVal) => {
						if (ruleVal.op == rule) {
							valid = false
							errorMessages.push(ruleVal.msg)
						}
					})
				}
			})
		})
		if (valid) {
			self.$el.find('.form-error').remove()
			$.post(self.data.endpoint, self.$el.serialize())
				.done((data) => {
					console.log(data)
					if (data.status) {
						self.showSuccess()
					} else {
						self.showError()
					}
				}).fail(() => [
					self.showError()
				])
		} else {
			self.$el.removeClass('is-in-process')
			self.$el.find('.form-error').remove()
			$.each(errorMessages, (i, v) => {
				self.$el.append($('<span></span>').addClass('form-error').text(v))
			})

		}
	}

	handleHideMessage(e, self) {
		self.$el.removeClass('is-success')
		self.el.reset()
	}

	showSuccess() {
		this.$el.removeClass('is-in-process')
		this.$el.addClass('is-success')
	}

	showError(message = 'Něco se pokazilo, zkus to znova') {
		this.$el.removeClass('is-in-process')
		this.$el.append($('<span></span>').addClass('form-error').text(message))
	}
}

module.exports = Form
