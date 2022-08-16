/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	
	return (
		<div {...useBlockProps()}  className='srp_admin_container'>
			<div>
				<h4>{ __( 'Payment Form', 'srp' ) }</h4>
				<p>{ __( 'Start collecting money right way!', 'srp' ) }</p>
			</div>
			<div className='srp_admin_form'>
				<p>
					<label for='srp_title'>{ __( 'Title', 'srp' ) }</label>
					<input id='srp_title' value={ attributes.title } onChange={ ( event ) => { setAttributes( { title: event.target.value } ) } } type='text' className='srp_title'/>
				</p>
				<p>
					<label for='srp_price'>{ __( 'Price', 'srp' ) }</label>
					<input id='srp_price' value={ attributes.price } onChange={ ( event ) => { setAttributes( { price: event.target.value } ) } } type='number' className='srp_price'/>
				</p>
				<p>
					<label for='srp_vat'>{ __( 'Vat (%)', 'srp' ) }</label>
					<input id='srp_vat' value={ attributes.vat } onChange={ ( event ) => { setAttributes( { vat: event.target.value } ) } } type='number' className='srp_vat'/>
				</p>
				<p>
					<label for='srp_recurring'>{ __( 'Allow Recurring', 'srp' ) }</label>
					<input id='srp_recurring' checked={attributes.recurring} onChange={ ( event ) => { 
						const target = event.target;
						const value = target.type === 'checkbox' ? target.checked : target.value;
						setAttributes( { 
							recurring: value
						} );


						} } type='checkbox' className='srp_recurring'/>
				</p>
				<p>
					<label for='srp_gateway'>{ __( 'Default Gateway', 'srp' ) }</label>
					<select id='srp_gateway' value={ attributes.gateway } onChange={ ( event ) => { setAttributes( { gateway: event.target.value } ) } } className='srp_gateway'>
						<option value='stripe'>{ __( 'Stripe', 'srp' ) }</option>
						<option value='paypal'>{ __( 'PayPal', 'srp' ) }</option>
						<option value='authorize'>{ __( 'Authorize.net', 'srp' ) }</option>
						<option value='2checkout'>{ __( '2 Checkout', 'srp' ) }</option>
					</select>
				</p>
				<p>
					<label for='srp_recurring_term'>{ __( 'Default Recurring Term', 'srp' ) }</label>
					<select id='srp_recurring_term' value={ attributes.recurring_term } onChange={ ( event ) => { setAttributes( { recurring_term: event.target.value } ) } } className='srp_recurring_term'>
						<option value='yearly'>{ __( 'Yearly', 'srp' ) }</option>
						<option value='monthly'>{ __( 'Monthly', 'srp' ) }</option>
						<option value='weekly'>{ __( 'Weekly', 'srp' ) }</option>
						<option value='daily'>{ __( 'Daily', 'srp' ) }</option>
					</select>
				</p>
			</div>
		</div>
	);
}
