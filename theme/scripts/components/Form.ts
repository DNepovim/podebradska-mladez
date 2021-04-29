import { Component, EventListeners, DelegateEvent } from '@mangoweb/scripts-base'
import serialize from 'form-serialize'

const MAIL_PATERN = RegExp('^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$' ,'i')
const RULES_ATTRIBUTE = 'data-nette-rules'

enum ClassList {
	IsInProcess = 'is-in-process',
	IsSuccess = 'is-success',
	FormError = 'form-error',
	IsHidden = 'is-hidden',
	FormField = 'form-field',
	FormErrorMessage = 'form-error-message'
}

enum NetteOp {
	Filled = ':filled',
	Email = ':email'
}

interface Props {
	endpoint: string
	postId: number
}

interface NetteRule {
	op: NetteOp
	msg: string
}

export class Form extends Component<Props, HTMLFormElement> {
	static componentName = 'Form'

	constructor(el: HTMLFormElement, props: Props) {
		super(el, props)
		this.el.noValidate = true
	}

	protected validators = {
		[NetteOp.Filled]: (value: string) => !value,
		[NetteOp.Email]: (value: string) => !!value && !MAIL_PATERN.test(value)
	}

	protected getListeners = (): EventListeners => [
		['focusout', '.form-input', this.handleFocusOutOfInput],
		['keydown', '.form-input', this.handleKeyDownOnInput],
		['click', '#form-submit', this.handleSubmitForm],
		['click', '.form-loader', this.handleOnLoaderClick],
	]

	handleFocusOutOfInput(e: DelegateEvent<'focusout'>): void {
		const input = e.delegateTarget as HTMLInputElement
		if (!input) {
			throw new Error('There is no input.')
		}
		this.showFieldMessage(input)
	}

	handleKeyDownOnInput(e: DelegateEvent<'keydown'>): void {
		const input = e.delegateTarget as HTMLInputElement
		this.showFieldMessage(input, "")
	}


	async handleSubmitForm(e: DelegateEvent<'click'>): Promise<void>{
		e.preventDefault()
		this.el.classList.add(ClassList.IsInProcess)

		const inputs =  this.getFormFields()

		let isValid: boolean = true
		inputs.forEach(input => {
			const isFieldValid = this.showFieldMessage(input)
			isValid = isValid && isFieldValid
		})

		if (isValid) {
			try {
				const response = await fetch(this.props.endpoint, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: this.getSerializedValues()
				})
			} catch(e) {
				this.showSuccess()
				throw new Error(e)
			}
			this.showSuccess()
		} else {
			this.hideLoader()
		}
	}

	handleOnLoaderClick() {
		this.el.classList.remove(ClassList.IsSuccess)
		this.el.reset()
	}

	showSuccess() {
		this.el.classList.remove(ClassList.IsInProcess)
		this.el.classList.add(ClassList.IsSuccess)
	}

	showError(): void {
		this.el.classList.remove(ClassList.IsInProcess)
		this.getChild(`#${ClassList.FormErrorMessage}`, HTMLElement).classList.remove(ClassList.IsHidden)
	}

	hideError(): void {
		this.getChild(`#${ClassList.FormErrorMessage}`, HTMLElement).classList.add(ClassList.IsHidden)
	}

	showFieldMessage(input: HTMLInputElement, message?: string): boolean {
		const errorMessage = message ?? this.getFieldErrorMessage(input)

		if (!errorMessage) {
			return true
		}

		try {
			const messageField = this.getFieldErrorElement(input)
			messageField.textContent = errorMessage
		} catch(e) {
			console.error("Missing error field.", e)
		}

		return false
	}

	getFieldErrorMessage(input: HTMLInputElement): string {
		const value = this.getFieldValueOr(input)
		const rules = this.getFieldRules(input)
		return rules.filter(rule => this.validators[rule.op](value)).map(rule => rule.msg).join(`\n`)
	}

	getFieldName(input: HTMLInputElement): string {
		const name = input.getAttribute('name')
		if (!name) {
			throw new Error('The input is missing a name attribute.')
		}
		return name
	}

	getFieldRules(input: HTMLInputElement): NetteRule[] {
		return JSON.parse(input.getAttribute(RULES_ATTRIBUTE) ?? "[]") as NetteRule[]
	}

	getFieldValueOr(input: HTMLInputElement, defaultValue = ""): string {
		return input.value ?? defaultValue
	}

	getFieldErrorElement(input: HTMLInputElement): HTMLSpanElement {
		const name = this.getFieldName(input)
		return this.getChild(`[data-for="frm-${name}"]`, HTMLSpanElement)
	}

	getFormFields(): NodeListOf<HTMLInputElement> {
		return this.getChildren(`.${ClassList.FormField}`)
	}

	getSerializedValues() {
		return serialize(this.el)
	}

	hideLoader() {
		this.el.classList.remove(ClassList.IsInProcess)
	}
}
