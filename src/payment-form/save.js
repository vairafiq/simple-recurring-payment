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
 
 /**
  * The save function defines the way in which the different attributes should
  * be combined into the final markup, which is then serialized by the block
  * editor into `post_content`.
  *
  * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
  *
  * @return {WPElement} Element to render.
  */
 export default function save ( args )
 {
	 var taxAmount = ( args.attributes.vat / 100 ) * args.attributes.price;
	 return (
		 <div className='srpContainer'>
			 <h1 className='srpHeader'>{ args.attributes.title }</h1>
			 <h2 className='srpSubHeader'>{ args.attributes.form_subtitle }</h2>
			 <h3 className='srpTitle'> Payment Details </h3>
			 <form className='srpPaymentForm' method="POST">
				<div className='srpDetails'>
					{ args.attributes.price &&
						<div className='srpDetailsBlock'>
							 <p className='srpDetailsLabel'>Amount: </p>
							 <input type='hidden' value={args.attributes.price} name='srpAmount'></input>
							 <p className='srpDetails'> $ { args.attributes.price } </p>
						</div>
					}
					{ !args.attributes.price && <div className='srpDetailsBlock'>
						 <p className='srpDetailsLabel'>{ __( 'Price', 'srp' ) }</p>
						 <input id='srp_price' placeholder='Enter amount' type='number' className='srpDetails' name='srpAmount'/>
					</div>
					}
					{ args.attributes.recurring &&
						<div className='srpDetailsBlock'>
							 <p className='srpDetailsLabel'> Subscription: </p>
							 <input type='hidden' value='yes' name='isRecurring'></input>
							<p className='srpDetails'> Yes </p>
						</div>
					}
					{ !args.attributes.recurring &&
						<div className='srpDetailsBlock'>
							 <p className='srpDetailsLabel'> Subscription: </p>
							 <input type='hidden' value='no' name='isRecurring'></input>
							<p className='srpDetails'> No </p>
						</div>
					}
					{ args.attributes.recurring &&
						<div className='srpDetailsBlock'>
							<p className='srpDetailsLabel'>Subscription Period: </p>
							<select id="srp_recurring_term" value={ args.attributes.recurring_term } className='srpDetails'>
							<option value='yearly'>{ __( 'Yearly', 'srp' ) }</option>
							<option value='monthly'>{ __( 'Monthly', 'srp' ) }</option>
							<option value='weekly'>{ __( 'Weekly', 'srp' ) }</option>
							<option value='daily'>{ __( 'Daily', 'srp' ) }</option>
							</select>
						</div>
					}
					<div className='srpDetailsBlock'>
						 <p className='srpDetailsLabel'>Gateway: </p>
						 <input type='hidden' value={args.attributes.gateway} name='srpGateway'></input>
						 <p className='srpDetails'> { args.attributes.gateway } </p>
					</div>
					{ args.attributes.vat &&
						<div className='srpDetailsBlock'>
							 <p className='srpDetailsLabel'>VAT ({ args.attributes.vat }%): </p>
							 <input type='hidden' value={ taxAmount } name='srpVat'></input>
							<p className='srpDetails'> $ { taxAmount } </p>
						</div>
					}
				</div>
				 {/* <label for="srpDonateAmount">Amount: </label>
				 <input type="number" placeholder="Enter Amount to Donate" name='srpDonateAmount'></input>
				 { args.attributes.recurring &&
					 <><input type="radio" name="srpRecurring" value="yes">Yes</input><input type="radio" name="srpRecurring" value="no">No</input></>
				 } */}
				 <input type='hidden' value={args.attributes.price + taxAmount} name='srpTotalAmount'></input>
				 <button type="submit" className='srpSubmitButton'> Donate Now </button>
			 </form>
		 </div>
	 );
 }