/**
 * When using the wp_editor on an admin screen that uses the block editor (Gutenberg),
 * the tinymce editor is not initialized correctly. This script is a workaround for hidden editors
 * that will be reinitialized when the user clicks the edit button.
 */
(function () {
	jQuery(function ($) {
		$('.queulat-wp-editor__edit-button').on('click', function (event) {
			const $wrap = $(this).closest('.queulat-wp-editor');
			const $preview = $wrap.find('.queulat-wp-editor__preview');
			const $editor = $wrap.find('.queulat-wp-editor__editor');
			const textareaId = $wrap
				.find('.queulat-wp-editor__editor')
				.find('textarea')
				.attr('id');
			const editorIndex = tinymce.editors.reduce((acc, editor, index) => {
				return editor.settings.selector === `#${textareaId}`
					? index
					: acc;
			}, null);
			const editorSettings = tinymce.editors[editorIndex].settings;
			tinymce.editors[editorIndex].remove();
			tinymce.init(editorSettings);
			$preview.addClass('hidden');
			$editor.removeClass('hidden');
		});
	});
})();
