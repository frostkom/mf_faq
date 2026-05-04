(function()
{
	var el = wp.element.createElement,
		registerBlockType = wp.blocks.registerBlockType
		InspectorControls = wp.blockEditor.InspectorControls,
		SelectControl = wp.components.SelectControl;

	registerBlockType('mf/faq',
	{
		title: script_faq_block_wp.block_title,
		description: script_faq_block_wp.block_description,
		icon: 'format-status',
		category: 'widgets',
		'attributes':
		{
			'align':
			{
				'type': 'string',
				'default': ''
			},
			'faq_parent':
			{
				'type': 'string',
				'default': ''
			}
		},
		'supports':
		{
			'html': false,
			'multiple': true,
			'align': true,
			'spacing':
			{
				'margin': true,
				'padding': true
			},
			'color':
			{
				'background': true,
				'gradients': false,
				'text': true
			},
			'defaultStylePicker': true,
			'typography':
			{
				'fontSize': true,
				'lineHeight': true
			},
			"__experimentalBorder":
			{
				"radius": true
			}
		},
		edit: function(props)
		{
			return el(
				'div',
				{className: 'wp_mf_block_container'},
				[
					el(
						InspectorControls,
						'div',
						el(
							SelectControl,
							{
								label: script_faq_block_wp.faq_parent_label,
								value: props.attributes.faq_parent,
								options: convert_php_array_to_block_js(script_faq_block_wp.arr_faq_parents),
								onChange: function(value)
								{
									props.setAttributes({faq_parent: value});
								}
							}
						),
					),
					el(
						'strong',
						{className: props.className},
						script_faq_block_wp.block_title
					)
				]
			);
		},
		save: function()
		{
			return null;
		}
	});
})();