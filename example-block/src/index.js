import { __ } from '@wordpress/i18n'
import { Fragment } from '@wordpress/element'
import { registerBlockType } from '@wordpress/blocks'
import { RichText, InnerBlocks } from '@wordpress/block-editor'

registerBlockType('example/block', {
  title: __('Example', 'example'),
  category: 'common',
  attributes: {
    heading: {
      type: 'string',
    },
  },
  edit: props => (
    <Fragment>
      <RichText
        tagName="h2"
        value={props.attributes.heading}
        onChange={heading => props.setAttributes({ heading })} />
      <InnerBlocks />
    </Fragment>
  ),
  save: props => <InnerBlocks.Content />,
})
