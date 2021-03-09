/**
 * Summernote StripTags
 *
 * This is a plugin for Summernote (www.summernote.org) WYSIWYG editor.
 * To strip unwanted HTML tags and attributes while pasting content in editor.
 *
 * @author Hitesh Aggarwal, Extenzine
 *
 */

(function (factory) {
   /* global define */
   if (typeof define === 'function' && define.amd) {
      // AMD. Register as an anonymous module.
      define(['jquery'], factory);
   } else if (typeof module === 'object' && module.exports) {
      // Node/CommonJS
      module.exports = factory(require('jquery'));
   } else {
      // Browser globals
      factory(window.jQuery);
   }
}(function ($) {
   $.extend($.summernote.options, {
      stripTags: ['font', 'style', 'embed', 'param', 'script', 'html', 'body', 'head', 'meta', 'title', 'link', 'iframe', 'applet', 'noframes', 'noscript', 'form', 'input', 'select', 'option', 'colgroup', 'col', 'std', 'xml:', 'st1:', 'o:', 'w:', 'v:'],
      stripAttributes: ['font', 'style', 'embed', 'param', 'script', 'html', 'body', 'head', 'meta', 'title', 'link', 'iframe', 'applet', 'noframes', 'noscript', 'form', 'input', 'select', 'option', 'colgroup', 'col', 'std', 'xml:', 'st1:', 'o:', 'w:', 'v:'],
      onAfterStripTags: function ($html) {
         return $html;
		  console.log($html);
      }
   });

   $.extend($.summernote.plugins, {
      'striptags': function (context) {
         var $note = context.layoutInfo.note;
         var $options = context.options;
         $note.on('summernote.paste', function (e, evt) {
            evt.preventDefault();
            var text = evt.originalEvent.clipboardData.getData('text/plain'), html = evt.originalEvent.clipboardData.getData('text/html');
            if (html) {
               var tagStripper = new RegExp('<[ /]*(' + $options.stripTags.join('|') + ')[^>]*>', 'gi'), attributeStripper = new RegExp(' (' + $options.stripAttributes.join('|') + ')(="[^"]*"|=\'[^\']*\'|=[^ ]+)?', 'gi'), commentStripper = new RegExp('<!--(.*)-->', 'g');
               html = html.toString().replace(commentStripper, '').replace(tagStripper, '').replace(attributeStripper, ' ').replace(/( class=(")?Mso[a-zA-Z]+(")?)/g, ' ').replace(/[\t ]+\</g, "<").replace(/\>[\t ]+\</g, "><").replace(/\>[\t ]+$/g, ">").replace(/[\u2018\u2019\u201A]/g, "'").replace(/[\u201C\u201D\u201E]/g, '"').replace(/\u2026/g, '...').replace(/[\u2013\u2014]/g, '-');
            }
            var $html = $('<div/>').html(html || text);
            $html = $options.onAfterStripTags($html);
            $note.summernote('insertNode', $html[0]);
            return false;
         });
      }
   });

}));