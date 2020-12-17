import { Component, EventListeners, DelegateEvent } from '@mangoweb/scripts-base'

interface Props {
	label: string
}

export class InputAddSymbol extends Component<Props> {
	static componentName = 'InputAddSymbol'

	protected getListeners = (): EventListeners => [
		['click', this.props.label, this.handleClick],
	]

	handleClick(e: DelegateEvent<'click'>): void {
		const label =  e.delegateTarget as HTMLElement

		if (!label) {
			return
		}

		const input = this.getChildren(`#${label.getAttribute('for')}`)[0] as HTMLInputElement
		input.value += label.innerText
	}
}
