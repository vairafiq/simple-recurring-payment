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
	 return (
		 <div>
			 <h1 className='srpHeader'>{ args.attributes.title }</h1>
			 <div className='srpDetails'>
				{args.attributes.price && <p className='srpDetails'> { args.attributes.price }</p>}
				 {args.attributes.recurring &&
					 <p className='srpDetails'> { args.attributes.recurring }</p>
				 }
				 { args.attributes.recurring_term &&
					 <p className='srpDetails'> { args.attributes.recurring_term }</p>
				 }
				 <p className='srpDetails'> { args.attributes.gateway }</p>
				 { args.attributes.vat &&
					 <p className='srpDetails'> { args.attributes.vat }</p>
				 }
			 </div>
			 <form className='srpHeader'	method="POST">
				 <input type="number" placeholder="Enter Amount to Donate"></input>
				 { args.attributes.recurring &&
					 <><input type="radio" name="srpRecurring" value="yes">Yes</input><input type="radio" name="srpRecurring" value="no">No</input></>
				 }
				 { args.attributes.recurring_term &&
					 <select id="srpRecurringTerm">
						 <option className="daily">Daily</option>
						 <option className="weekly">Weekly</option>
						 <option className="monthly">Monthly</option>
						 <option className="yearly">Yearly</option>
					 </select>
				 }
				 <button type="submit"> Donate Now </button>
			 </form>
		 </div>
	 );
 }