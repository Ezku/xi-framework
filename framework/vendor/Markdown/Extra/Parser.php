<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_markdown.php';

class Markdown_Extra_Parser extends Markdown_Parser {

    # Prefix for footnote ids.
    var $fn_id_prefix = "";
    
    # Optional title attribute for footnote links and backlinks.
    var $fn_link_title = MARKDOWN_FN_LINK_TITLE;
    var $fn_backlink_title = MARKDOWN_FN_BACKLINK_TITLE;
    
    # Optional class attribute for footnote links and backlinks.
    var $fn_link_class = MARKDOWN_FN_LINK_CLASS;
    var $fn_backlink_class = MARKDOWN_FN_BACKLINK_CLASS;


    function MarkdownExtra_Parser() {
    #
    # Constructor function. Initialize the parser object.
    #
        # Add extra escapable characters before parent constructor 
        # initialize the table.
        $this->escape_chars .= ':|';
        
        # Insert extra document, block, and span transformations. 
        # Parent constructor will do the sorting.
        $this->document_gamut += array(
            "stripFootnotes"     => 15,
            "stripAbbreviations" => 25,
            "appendFootnotes"    => 50,
            );
        $this->block_gamut += array(
            "doTables"           => 15,
            "doDefLists"         => 45,
            );
        $this->span_gamut += array(
            "doFootnotes"        =>  4,
            "doAbbreviations"    =>  5,
            );
        
        parent::Markdown_Parser();
    }
    
    
    # Extra hashes used during extra transformations.
    var $footnotes = array();
    var $footnotes_ordered = array();
    var $abbr_desciptions = array();
    var $abbr_matches = array();
    var $html_cleans = array();
    
    
    function transform($text) {
    #
    # Added clear to the new $html_hashes, reordered `hashHTMLBlocks` before 
    # blank line stripping and added extra parameter to `runBlockGamut`.
    #
        # Clear the global hashes. If we don't clear these, you get conflicts
        # from other articles when generating a page which contains more than
        # one article (e.g. an index page that shows the N most recent
        # articles):
        $this->footnotes = array();
        $this->footnotes_ordered = array();
        $this->abbr_desciptions = array();
        $this->abbr_matches = array();
        $this->html_cleans = array();

        return parent::transform($text);
    }
    
    
    ### HTML Block Parser ###
    
    # Tags that are always treated as block tags:
    var $block_tags = 'p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|address|form|fieldset|iframe|hr|legend';
    
    # Tags treated as block tags only if the opening tag is alone on it's line:
    var $context_block_tags = 'script|noscript|math|ins|del';
    
    # Tags where markdown="1" default to span mode:
    var $contain_span_tags = 'p|h[1-6]|li|dd|dt|td|th|legend|address';
    
    # Tags which must not have their contents modified, no matter where 
    # they appear:
    var $clean_tags = 'script|math';
    
    # Tags that do not need to be closed.
    var $auto_close_tags = 'hr|img';
    

    function hashHTMLBlocks($text) {
    #
    # Hashify HTML Blocks and "clean tags".
    #
    # We only want to do this for block-level HTML tags, such as headers,
    # lists, and tables. That's because we still want to wrap <p>s around
    # "paragraphs" that are wrapped in non-block-level tags, such as anchors,
    # phrase emphasis, and spans. The list of tags we're looking for is
    # hard-coded.
    #
    # This works by calling _HashHTMLBlocks_InMarkdown, which then calls
    # _HashHTMLBlocks_InHTML when it encounter block tags. When the markdown="1" 
    # attribute is found whitin a tag, _HashHTMLBlocks_InHTML calls back
    #  _HashHTMLBlocks_InMarkdown to handle the Markdown syntax within the tag.
    # These two functions are calling each other. It's recursive!
    #
        #
        # Call the HTML-in-Markdown hasher.
        #
        list($text, ) = $this->_hashHTMLBlocks_inMarkdown($text);
        
        return $text;
    }
    function _hashHTMLBlocks_inMarkdown($text, $indent = 0, 
                                        $enclosing_tag = '', $span = false)
    {
    #
    # Parse markdown text, calling _HashHTMLBlocks_InHTML for block tags.
    #
    # *   $indent is the number of space to be ignored when checking for code 
    #     blocks. This is important because if we don't take the indent into 
    #     account, something like this (which looks right) won't work as expected:
    #
    #     <div>
    #         <div markdown="1">
    #         Hello World.  <-- Is this a Markdown code block or text?
    #         </div>  <-- Is this a Markdown code block or a real tag?
    #     <div>
    #
    #     If you don't like this, just don't indent the tag on which
    #     you apply the markdown="1" attribute.
    #
    # *   If $enclosing_tag is not empty, stops at the first unmatched closing 
    #     tag with that name. Nested tags supported.
    #
    # *   If $span is true, text inside must treated as span. So any double 
    #     newline will be replaced by a single newline so that it does not create 
    #     paragraphs.
    #
    # Returns an array of that form: ( processed text , remaining text )
    #
        if ($text === '') return array('', '');

        # Regex to check for the presense of newlines around a block tag.
        $newline_match_before = '/(?:^\n?|\n\n)*$/';
        $newline_match_after = 
            '{
                ^                       # Start of text following the tag.
                (?:[ ]*<!--.*?-->)?     # Optional comment.
                [ ]*\n                  # Must be followed by newline.
            }xs';
        
        # Regex to match any tag.
        $block_tag_match =
            '{
                (                   # $2: Capture hole tag.
                    </?                 # Any opening or closing tag.
                        (?:             # Tag name.
                            '.$this->block_tags.'           |
                            '.$this->context_block_tags.'   |
                            '.$this->clean_tags.'           |
                            (?!\s)'.$enclosing_tag.'
                        )
                        \s*             # Whitespace.
                        (?:
                            ".*?"       |   # Double quotes (can contain `>`)
                            \'.*?\'     |   # Single quotes (can contain `>`)
                            .+?             # Anything but quotes and `>`.
                        )*?
                    >                   # End of tag.
                |
                    <!--    .*?     --> # HTML Comment
                |
                    <\?.*?\?> | <%.*?%> # Processing instruction
                |
                    <!\[CDATA\[.*?\]\]> # CData Block
                )
            }xs';

        
        $depth = 0;     # Current depth inside the tag tree.
        $parsed = "";   # Parsed text that will be returned.

        #
        # Loop through every tag until we find the closing tag of the parent
        # or loop until reaching the end of text if no parent tag specified.
        #
        do {
            #
            # Split the text using the first $tag_match pattern found.
            # Text before  pattern will be first in the array, text after
            # pattern will be at the end, and between will be any catches made 
            # by the pattern.
            #
            $parts = preg_split($block_tag_match, $text, 2, 
                                PREG_SPLIT_DELIM_CAPTURE);
            
            # If in Markdown span mode, add a empty-string span-level hash 
            # after each newline to prevent triggering any block element.
            if ($span) {
                $newline = $this->hashSpan("") . "\n";
                $parts[0] = str_replace("\n", $newline, $parts[0]);
            }
            
            $parsed .= $parts[0]; # Text before current tag.
            
            # If end of $text has been reached. Stop loop.
            if (count($parts) < 3) {
                $text = "";
                break;
            }
            
            $tag  = $parts[1]; # Tag to handle.
            $text = $parts[2]; # Remaining text after current tag.
            
            #
            # Check for: Tag inside code block or span
            #
            if (# Find current paragraph
                preg_match('/(?>^\n?|\n\n)((?>.\n?)+?)$/', $parsed, $matches) &&
                (
                # Then match in it either a code block...
                preg_match('/^ {'.($indent+4).'}.*(?>\n {'.($indent+4).'}.*)*'.
                            '(?!\n)$/', $matches[1], $x) ||
                # ...or unbalenced code span markers. (the regex matches balenced)
                !preg_match('/^(?>[^`]+|(`+)(?>[^`]+|(?!\1[^`])`)*?\1(?!`))*$/s',
                             $matches[1])
                ))
            {
                # Tag is in code block or span and may not be a tag at all. So we
                # simply skip the first char (should be a `<`).
                $parsed .= $tag{0};
                $text = substr($tag, 1) . $text; # Put back $tag minus first char.
            }
            #
            # Check for: Opening Block level tag or
            #            Opening Content Block tag (like ins and del) 
            #               used as a block tag (tag is alone on it's line).
            #
            else if (preg_match("{^<(?:$this->block_tags)\b}", $tag) ||
                (   preg_match("{^<(?:$this->context_block_tags)\b}", $tag) &&
                    preg_match($newline_match_before, $parsed) &&
                    preg_match($newline_match_after, $text) )
                )
            {
                # Need to parse tag and following text using the HTML parser.
                list($block_text, $text) = 
                    $this->_hashHTMLBlocks_inHTML($tag . $text, "hashBlock", true);
                
                # Make sure it stays outside of any paragraph by adding newlines.
                $parsed .= "\n\n$block_text\n\n";
            }
            #
            # Check for: Clean tag (like script, math)
            #            HTML Comments, processing instructions.
            #
            else if (preg_match("{^<(?:$this->clean_tags)\b}", $tag) ||
                $tag{1} == '!' || $tag{1} == '?')
            {
                # Need to parse tag and following text using the HTML parser.
                # (don't check for markdown attribute)
                list($block_text, $text) = 
                    $this->_hashHTMLBlocks_inHTML($tag . $text, "hashClean", false);
                
                $parsed .= $block_text;
            }
            #
            # Check for: Tag with same name as enclosing tag.
            #
            else if ($enclosing_tag !== '' &&
                # Same name as enclosing tag.
                preg_match("{^</?(?:$enclosing_tag)\b}", $tag))
            {
                #
                # Increase/decrease nested tag count.
                #
                if ($tag{1} == '/')                     $depth--;
                else if ($tag{strlen($tag)-2} != '/')   $depth++;

                if ($depth < 0) {
                    #
                    # Going out of parent element. Clean up and break so we
                    # return to the calling function.
                    #
                    $text = $tag . $text;
                    break;
                }
                
                $parsed .= $tag;
            }
            else {
                $parsed .= $tag;
            }
        } while ($depth >= 0);
        
        return array($parsed, $text);
    }
    function _hashHTMLBlocks_inHTML($text, $hash_method, $md_attr) {
    #
    # Parse HTML, calling _HashHTMLBlocks_InMarkdown for block tags.
    #
    # *   Calls $hash_method to convert any blocks.
    # *   Stops when the first opening tag closes.
    # *   $md_attr indicate if the use of the `markdown="1"` attribute is allowed.
    #     (it is not inside clean tags)
    #
    # Returns an array of that form: ( processed text , remaining text )
    #
        if ($text === '') return array('', '');
        
        # Regex to match `markdown` attribute inside of a tag.
        $markdown_attr_match = '
            {
                \s*         # Eat whitespace before the `markdown` attribute
                markdown
                \s*=\s*
                (["\'])     # $1: quote delimiter       
                (.*?)       # $2: attribute value
                \1          # matching delimiter    
            }xs';
        
        # Regex to match any tag.
        $tag_match = '{
                (                   # $2: Capture hole tag.
                    </?                 # Any opening or closing tag.
                        [\w:$]+         # Tag name.
                        \s*             # Whitespace.
                        (?:
                            ".*?"       |   # Double quotes (can contain `>`)
                            \'.*?\'     |   # Single quotes (can contain `>`)
                            .+?             # Anything but quotes and `>`.
                        )*?
                    >                   # End of tag.
                |
                    <!--    .*?     --> # HTML Comment
                |
                    <\?.*?\?> | <%.*?%> # Processing instruction
                |
                    <!\[CDATA\[.*?\]\]> # CData Block
                )
            }xs';
        
        $original_text = $text;     # Save original text in case of faliure.
        
        $depth      = 0;    # Current depth inside the tag tree.
        $block_text = "";   # Temporary text holder for current text.
        $parsed     = "";   # Parsed text that will be returned.

        #
        # Get the name of the starting tag.
        #
        if (preg_match("/^<([\w:$]*)\b/", $text, $matches))
            $base_tag_name = $matches[1];

        #
        # Loop through every tag until we find the corresponding closing tag.
        #
        do {
            #
            # Split the text using the first $tag_match pattern found.
            # Text before  pattern will be first in the array, text after
            # pattern will be at the end, and between will be any catches made 
            # by the pattern.
            #
            $parts = preg_split($tag_match, $text, 2, PREG_SPLIT_DELIM_CAPTURE);
            
            if (count($parts) < 3) {
                #
                # End of $text reached with unbalenced tag(s).
                # In that case, we return original text unchanged and pass the
                # first character as filtered to prevent an infinite loop in the 
                # parent function.
                #
                return array($original_text{0}, substr($original_text, 1));
            }
            
            $block_text .= $parts[0]; # Text before current tag.
            $tag         = $parts[1]; # Tag to handle.
            $text        = $parts[2]; # Remaining text after current tag.
            
            #
            # Check for: Auto-close tag (like <hr/>)
            #            Comments and Processing Instructions.
            #
            if (preg_match("{^</?(?:$this->auto_close_tags)\b}", $tag) ||
                $tag{1} == '!' || $tag{1} == '?')
            {
                # Just add the tag to the block as if it was text.
                $block_text .= $tag;
            }
            else {
                #
                # Increase/decrease nested tag count. Only do so if
                # the tag's name match base tag's.
                #
                if (preg_match("{^</?$base_tag_name\b}", $tag)) {
                    if ($tag{1} == '/')                     $depth--;
                    else if ($tag{strlen($tag)-2} != '/')   $depth++;
                }
                
                #
                # Check for `markdown="1"` attribute and handle it.
                #
                if ($md_attr && 
                    preg_match($markdown_attr_match, $tag, $attr_matches) &&
                    preg_match('/^1|block|span$/', $attr_matches[2]))
                {
                    # Remove `markdown` attribute from opening tag.
                    $tag = preg_replace($markdown_attr_match, '', $tag);
                    
                    # Check if text inside this tag must be parsed in span mode.
                    $this->mode = $attr_matches[2];
                    $span_mode = $this->mode == 'span' || $this->mode != 'block' &&
                        preg_match("{^<(?:$this->contain_span_tags)\b}", $tag);
                    
                    # Calculate indent before tag.
                    preg_match('/(?:^|\n)( *?)(?! ).*?$/', $block_text, $matches);
                    $indent = strlen($matches[1]);
                    
                    # End preceding block with this tag.
                    $block_text .= $tag;
                    $parsed .= $this->$hash_method($block_text);
                    
                    # Get enclosing tag name for the ParseMarkdown function.
                    preg_match('/^<([\w:$]*)\b/', $tag, $matches);
                    $tag_name = $matches[1];
                    
                    # Parse the content using the HTML-in-Markdown parser.
                    list ($block_text, $text)
                        = $this->_hashHTMLBlocks_inMarkdown($text, $indent, 
                                                        $tag_name, $span_mode);
                    
                    # Outdent markdown text.
                    if ($indent > 0) {
                        $block_text = preg_replace("/^[ ]{1,$indent}/m", "", 
                                                    $block_text);
                    }
                    
                    # Append tag content to parsed text.
                    if (!$span_mode)    $parsed .= "\n\n$block_text\n\n";
                    else                $parsed .= "$block_text";
                    
                    # Start over a new block.
                    $block_text = "";
                }
                else $block_text .= $tag;
            }
            
        } while ($depth > 0);
        
        #
        # Hash last block text that wasn't processed inside the loop.
        #
        $parsed .= $this->$hash_method($block_text);
        
        return array($parsed, $text);
    }


    function hashClean($text) {
    #
    # Called whenever a tag must be hashed when a function insert a "clean" tag
    # in $text, it pass through this function and is automaticaly escaped, 
    # blocking invalid nested overlap.
    #
        # Swap back any tag hash found in $text so we do not have to `unhash`
        # multiple times at the end.
        $text = $this->unhash($text);
        
        # Then hash the tag.
        $key = md5($text);
        $this->html_cleans[$key] = $text;
        $this->html_hashes[$key] = $text;
        return $key; # String that will replace the clean tag.
    }


    function doHeaders($text) {
    #
    # Redefined to add id attribute support.
    #
        # Setext-style headers:
        #     Header 1  {#header1}
        #     ========
        #  
        #     Header 2  {#header2}
        #     --------
        #
        $text = preg_replace_callback(
            '{ (^.+?) (?:[ ]+\{\#([-_:a-zA-Z0-9]+)\})? [ \t]*\n=+[ \t]*\n+ }mx',
            array(&$this, '_doHeaders_callback_setext_h1'), $text);
        $text = preg_replace_callback(
            '{ (^.+?) (?:[ ]+\{\#([-_:a-zA-Z0-9]+)\})? [ \t]*\n-+[ \t]*\n+ }mx',
            array(&$this, '_doHeaders_callback_setext_h2'), $text);

        # atx-style headers:
        #   # Header 1        {#header1}
        #   ## Header 2       {#header2}
        #   ## Header 2 with closing hashes ##  {#header3}
        #   ...
        #   ###### Header 6   {#header2}
        #
        $text = preg_replace_callback('{
                ^(\#{1,6})  # $1 = string of #\'s
                [ \t]*
                (.+?)       # $2 = Header text
                [ \t]*
                \#*         # optional closing #\'s (not counted)
                (?:[ ]+\{\#([-_:a-zA-Z0-9]+)\})? # id attribute
                [ \t]*
                \n+
            }xm',
            array(&$this, '_doHeaders_callback_atx'), $text);

        return $text;
    }
    function _doHeaders_attr($attr) {
        if (empty($attr))  return "";
        return " id=\"$attr\"";
    }
    function _doHeaders_callback_setext_h1($matches) {
        $attr  = $this->_doHeaders_attr($id =& $matches[2]);
        $block = "<h1$attr>".$this->runSpanGamut($matches[1])."</h1>";
        return "\n" . $this->hashBlock($block) . "\n\n";
    }
    function _doHeaders_callback_setext_h2($matches) {
        $attr  = $this->_doHeaders_attr($id =& $matches[2]);
        $block = "<h2$attr>".$this->runSpanGamut($matches[1])."</h2>";
        return "\n" . $this->hashBlock($block) . "\n\n";
    }
    function _doHeaders_callback_atx($matches) {
        $level = strlen($matches[1]);
        $attr  = $this->_doHeaders_attr($id =& $matches[3]);
        $block = "<h$level$attr>".$this->runSpanGamut($matches[2])."</h$level>";
        return "\n" . $this->hashBlock($block) . "\n\n";
    }


    function doTables($text) {
    #
    # Form HTML tables.
    #
        $less_than_tab = $this->tab_width - 1;
        #
        # Find tables with leading pipe.
        #
        #   | Header 1 | Header 2
        #   | -------- | --------
        #   | Cell 1   | Cell 2
        #   | Cell 3   | Cell 4
        #
        $text = preg_replace_callback('
            {
                ^                           # Start of a line
                [ ]{0,'.$less_than_tab.'}   # Allowed whitespace.
                [|]                         # Optional leading pipe (present)
                (.+) \n                     # $1: Header row (at least one pipe)
                
                [ ]{0,'.$less_than_tab.'}   # Allowed whitespace.
                [|] ([ ]*[-:]+[-| :]*) \n   # $2: Header underline
                
                (                           # $3: Cells
                    (?:
                        [ ]*                # Allowed whitespace.
                        [|] .* \n           # Row content.
                    )*
                )
                (?=\n|\Z)                   # Stop at final double newline.
            }xm',
            array(&$this, '_doTable_leadingPipe_callback'), $text);
        
        #
        # Find tables without leading pipe.
        #
        #   Header 1 | Header 2
        #   -------- | --------
        #   Cell 1   | Cell 2
        #   Cell 3   | Cell 4
        #
        $text = preg_replace_callback('
            {
                ^                           # Start of a line
                [ ]{0,'.$less_than_tab.'}   # Allowed whitespace.
                (\S.*[|].*) \n              # $1: Header row (at least one pipe)
                
                [ ]{0,'.$less_than_tab.'}   # Allowed whitespace.
                ([-:]+[ ]*[|][-| :]*) \n    # $2: Header underline
                
                (                           # $3: Cells
                    (?:
                        .* [|] .* \n        # Row content
                    )*
                )
                (?=\n|\Z)                   # Stop at final double newline.
            }xm',
            array(&$this, '_DoTable_callback'), $text);

        return $text;
    }
    function _doTable_leadingPipe_callback($matches) {
        $head       = $matches[1];
        $underline  = $matches[2];
        $content    = $matches[3];
        
        # Remove leading pipe for each row.
        $content    = preg_replace('/^ *[|]/m', '', $content);
        
        return $this->_doTable_callback(array($matches[0], $head, $underline, $content));
    }
    function _doTable_callback($matches) {
        $head       = $matches[1];
        $underline  = $matches[2];
        $content    = $matches[3];

        # Remove any tailing pipes for each line.
        $head       = preg_replace('/[|] *$/m', '', $head);
        $underline  = preg_replace('/[|] *$/m', '', $underline);
        $content    = preg_replace('/[|] *$/m', '', $content);
        
        # Reading alignement from header underline.
        $separators = preg_split('/ *[|] */', $underline);
        foreach ($separators as $n => $s) {
            if (preg_match('/^ *-+: *$/', $s))      $attr[$n] = ' align="right"';
            else if (preg_match('/^ *:-+: *$/', $s))$attr[$n] = ' align="center"';
            else if (preg_match('/^ *:-+ *$/', $s)) $attr[$n] = ' align="left"';
            else                                    $attr[$n] = '';
        }
        
        # Creating code spans before splitting the row is an easy way to 
        # handle a code span containg pipes.
        $head   = $this->doCodeSpans($head);
        $headers    = preg_split('/ *[|] */', $head);
        $col_count  = count($headers);
        
        # Write column headers.
        $text = "<table>\n";
        $text .= "<thead>\n";
        $text .= "<tr>\n";
        foreach ($headers as $n => $header)
            $text .= "  <th$attr[$n]>".$this->runSpanGamut(trim($header))."</th>\n";
        $text .= "</tr>\n";
        $text .= "</thead>\n";
        
        # Split content by row.
        $rows = explode("\n", trim($content, "\n"));
        
        $text .= "<tbody>\n";
        foreach ($rows as $row) {
            # Creating code spans before splitting the row is an easy way to 
            # handle a code span containg pipes.
            $row = $this->doCodeSpans($row);
            
            # Split row by cell.
            $row_cells = preg_split('/ *[|] */', $row, $col_count);
            $row_cells = array_pad($row_cells, $col_count, '');
            
            $text .= "<tr>\n";
            foreach ($row_cells as $n => $cell)
                $text .= "  <td$attr[$n]>".$this->runSpanGamut(trim($cell))."</td>\n";
            $text .= "</tr>\n";
        }
        $text .= "</tbody>\n";
        $text .= "</table>";
        
        return $this->hashBlock($text) . "\n";
    }

    
    function doDefLists($text) {
    #
    # Form HTML definition lists.
    #
        $less_than_tab = $this->tab_width - 1;

        # Re-usable pattern to match any entire dl list:
        $whole_list = '
            (                               # $1 = whole list
              (                             # $2
                [ ]{0,'.$less_than_tab.'}
                ((?>.*\S.*\n)+)             # $3 = defined term
                \n?
                [ ]{0,'.$less_than_tab.'}:[ ]+ # colon starting definition
              )
              (?s:.+?)
              (                             # $4
                  \z
                |
                  \n{2,}
                  (?=\S)
                  (?!                       # Negative lookahead for another term
                    [ ]{0,'.$less_than_tab.'}
                    (?: \S.*\n )+?          # defined term
                    \n?
                    [ ]{0,'.$less_than_tab.'}:[ ]+ # colon starting definition
                  )
                  (?!                       # Negative lookahead for another definition
                    [ ]{0,'.$less_than_tab.'}:[ ]+ # colon starting definition
                  )
              )
            )
        '; // mx

        $text = preg_replace_callback('{
                (?:(?<=\n\n)|\A\n?)
                '.$whole_list.'
            }mx',
            array(&$this, '_doDefLists_callback'), $text);

        return $text;
    }
    function _doDefLists_callback($matches) {
        # Re-usable patterns to match list item bullets and number markers:
        $list = $matches[1];
        
        # Turn double returns into triple returns, so that we can make a
        # paragraph for the last item in a list, if necessary:
        $result = trim($this->processDefListItems($list));
        $result = "<dl>\n" . $result . "\n</dl>";
        return $this->hashBlock($result) . "\n\n";
    }


    function processDefListItems($list_str) {
    #
    #   Process the contents of a single definition list, splitting it
    #   into individual term and definition list items.
    #
        $less_than_tab = $this->tab_width - 1;
        
        # trim trailing blank lines:
        $list_str = preg_replace("/\n{2,}\\z/", "\n", $list_str);

        # Process definition terms.
        $list_str = preg_replace_callback('{
            (?:\n\n+|\A\n?)                 # leading line
            (                               # definition terms = $1
                [ ]{0,'.$less_than_tab.'}   # leading whitespace
                (?![:][ ]|[ ])              # negative lookahead for a definition 
                                            #   mark (colon) or more whitespace.
                (?: \S.* \n)+?              # actual term (not whitespace). 
            )           
            (?=\n?[ ]{0,3}:[ ])             # lookahead for following line feed 
                                            #   with a definition mark.
            }xm',
            array(&$this, '_processDefListItems_callback_dt'), $list_str);

        # Process actual definitions.
        $list_str = preg_replace_callback('{
            \n(\n+)?                        # leading line = $1
            [ ]{0,'.$less_than_tab.'}       # whitespace before colon
            [:][ ]+                         # definition mark (colon)
            ((?s:.+?))                      # definition text = $2
            (?= \n+                         # stop at next definition mark,
                (?:                         # next term or end of text
                    [ ]{0,'.$less_than_tab.'} [:][ ]    |
                    <dt> | \z
                )                       
            )                   
            }xm',
            array(&$this, '_processDefListItems_callback_dd'), $list_str);

        return $list_str;
    }
    function _processDefListItems_callback_dt($matches) {
        $terms = explode("\n", trim($matches[1]));
        $text = '';
        foreach ($terms as $term) {
            $term = $this->runSpanGamut(trim($term));
            $text .= "\n<dt>" . $term . "</dt>";
        }
        return $text . "\n";
    }
    function _processDefListItems_callback_dd($matches) {
        $leading_line   = $matches[1];
        $def            = $matches[2];

        if ($leading_line || preg_match('/\n{2,}/', $def)) {
            $def = $this->runBlockGamut($this->outdent($def . "\n\n"));
            $def = "\n". $def ."\n";
        }
        else {
            $def = rtrim($def);
            $def = $this->runSpanGamut($this->outdent($def));
        }

        return "\n<dd>" . $def . "</dd>\n";
    }


    function doItalicsAndBold($text) {
    #
    # Redefined to change emphasis by underscore behaviour so that it does not 
    # work in the middle of a word.
    #
        # <strong> must go first:
        $text = preg_replace_callback(array(
            '{
                (                       # $1: Marker
                    (?<![a-zA-Z0-9])    # Not preceded by alphanum
                    (?<!__)             #   or by two marker chars.
                    __
                )
                (?=\S)                  # Not followed by whitespace 
                (?!__)                  #   or two others marker chars.
                (                       # $2: Content
                    (?:
                        [^_]+?          # Anthing not em markers.
                    |
                                        # Balence any regular _ emphasis inside.
                        (?<![a-zA-Z0-9]) _ (?=\S) (.+?) 
                        (?<=\S) _ (?![a-zA-Z0-9])
                    |
                        ___+
                    )+?
                )
                (?<=\S) __              # End mark not preceded by whitespace.
                (?![a-zA-Z0-9])         # Not followed by alphanum
                (?!__)                  #   or two others marker chars.
            }sx',
            '{
                ( (?<!\*\*) \*\* )      # $1: Marker (not preceded by two *)
                (?=\S)                  # Not followed by whitespace 
                (?!\1)                  #   or two others marker chars.
                (                       # $2: Content
                    (?:
                        [^*]+?          # Anthing not em markers.
                    |
                                        # Balence any regular * emphasis inside.
                        \* (?=\S) (.+?) (?<=\S) \*
                    )+?
                )
                (?<=\S) \*\*            # End mark not preceded by whitespace.
            }sx',
            ),
            array(&$this, '_doItalicAndBold_strong_callback'), $text);
        # Then <em>:
        $text = preg_replace_callback(array(
            '{ ( (?<![a-zA-Z0-9])(?<!_)_ ) (?=\S) (?! \1) (.+?) (?<=\S) \1(?![a-zA-Z0-9]) }sx',
            '{ ( (?<!\*)\* ) (?=\S) (?! \1) (.+?) (?<=\S) \1 }sx',
            ),
            array(&$this, '_doItalicAndBold_em_callback'), $text);

        return $text;
    }


    function formParagraphs($text) {
    #
    #   Params:
    #       $text - string to process with html <p> tags
    #
        # Strip leading and trailing lines:
        $text = preg_replace(array('/\A\n+/', '/\n+\z/'), '', $text);
        
        $grafs = preg_split('/\n{2,}/', $text, -1, PREG_SPLIT_NO_EMPTY);

        #
        # Wrap <p> tags and unhashify HTML blocks
        #
        foreach ($grafs as $key => $value) {
            $value = trim($this->runSpanGamut($value));
            
            # Check if this should be enclosed in a paragraph.
            # Clean tag hashes & block tag hashes are left alone.
            $clean_key = $value;
            $block_key = substr($value, 0, 32);
            
            $is_p = (!isset($this->html_blocks[$block_key]) && 
                     !isset($this->html_cleans[$clean_key]));
            
            if ($is_p) {
                $value = "<p>$value</p>";
            }
            $grafs[$key] = $value;
        }
        
        # Join grafs in one text, then unhash HTML tags. 
        $text = implode("\n\n", $grafs);
        
        # Finish by removing any tag hashes still present in $text.
        $text = $this->unhash($text);
        
        return $text;
    }
    
    
    ### Footnotes
    
    function stripFootnotes($text) {
    #
    # Strips link definitions from text, stores the URLs and titles in
    # hash references.
    #
        $less_than_tab = $this->tab_width - 1;

        # Link defs are in the form: [^id]: url "optional title"
        $text = preg_replace_callback('{
            ^[ ]{0,'.$less_than_tab.'}\[\^(.+?)\][ ]?:  # note_id = $1
              [ \t]*
              \n?                   # maybe *one* newline
            (                       # text = $2 (no blank lines allowed)
                (?:                 
                    .+              # actual text
                |
                    \n              # newlines but 
                    (?!\[\^.+?\]:\s)# negative lookahead for footnote marker.
                    (?!\n+[ ]{0,3}\S)# ensure line is not blank and followed 
                                    # by non-indented content
                )*
            )       
            }xm',
            array(&$this, '_stripFootnotes_callback'),
            $text);
        return $text;
    }
    function _stripFootnotes_callback($matches) {
        $note_id = $matches[1];
        $this->footnotes[$note_id] = $this->outdent($matches[2]);
        return ''; # String that will replace the block
    }


    function doFootnotes($text) {
    #
    # Replace footnote references in $text [^id] with a special text-token 
    # which will be can be
    #
        $text = preg_replace('{\[\^(.+?)\]}', "a\0fn:\\1\0z", $text);
        return $text;
    }

    
    function appendFootnotes($text) {
    #
    # Append footnote list to text.
    #
        $text = preg_replace_callback('{a\0fn:(.*?)\0z}', 
            array(&$this, '_appendFootnotes_callback'), $text);
    
        if (!empty($this->footnotes_ordered)) {
            $text .= "\n\n";
            $text .= "<div class=\"footnotes\">\n";
            $text .= "<hr". MARKDOWN_EMPTY_ELEMENT_SUFFIX ."\n";
            $text .= "<ol>\n\n";
            
            $attr = " rev=\"footnote\"";
            if ($this->fn_backlink_class != "") {
                $class = $this->fn_backlink_class;
                $class = $this->encodeAmpsAndAngles($class);
                $class = str_replace('"', '&quot;', $class);
                $attr .= " class=\"$class\"";
            }
            if ($this->fn_backlink_title != "") {
                $title = $this->fn_backlink_title;
                $title = $this->encodeAmpsAndAngles($title);
                $title = str_replace('"', '&quot;', $title);
                $attr .= " title=\"$title\"";
            }
            $num = 0;
            
            foreach ($this->footnotes_ordered as $note_id => $footnote) {
                $footnote .= "\n"; # Need to append newline before parsing.
                $footnote = $this->runBlockGamut("$footnote\n");
                
                $attr2 = str_replace("%%", ++$num, $attr);
                
                # Add backlink to last paragraph; create new paragraph if needed.
                $backlink = "<a href=\"#fnref:$note_id\"$attr2>&#8617;</a>";
                if (preg_match('{</p>$}', $footnote)) {
                    $footnote = substr($footnote, 0, -4) . "&#160;$backlink</p>";
                } else {
                    $footnote .= "\n\n<p>$backlink</p>";
                }
                
                $text .= "<li id=\"fn:$note_id\">\n";
                $text .= $footnote . "\n";
                $text .= "</li>\n\n";
            }
            
            $text .= "</ol>\n";
            $text .= "</div>";
            
            $text = preg_replace('{a\{fn:(.*?)\}z}', '[^\\1]', $text);
        }
        return $text;
    }
    function _appendFootnotes_callback($matches) {
        $node_id = $this->fn_id_prefix . $matches[1];
        
        # Create footnote marker only if it has a corresponding footnote *and*
        # the footnote hasn't been used by another marker.
        if (isset($this->footnotes[$node_id])) {
            # Transfert footnote content to the ordered list.
            $this->footnotes_ordered[$node_id] = $this->footnotes[$node_id];
            unset($this->footnotes[$node_id]);
            
            $num = count($this->footnotes_ordered);
            $attr = " rel=\"footnote\"";
            if ($this->fn_link_class != "") {
                $class = $this->fn_link_class;
                $class = $this->encodeAmpsAndAngles($class);
                $class = str_replace('"', '&quot;', $class);
                $attr .= " class=\"$class\"";
            }
            if ($this->fn_link_title != "") {
                $title = $this->fn_link_title;
                $title = $this->encodeAmpsAndAngles($title);
                $title = str_replace('"', '&quot;', $title);
                $attr .= " title=\"$title\"";
            }
            $attr = str_replace("%%", $num, $attr);
            
            return
                "<sup id=\"fnref:$node_id\">".
                "<a href=\"#fn:$node_id\"$attr>$num</a>".
                "</sup>";
        }
        
        return "[^".$matches[1]."]";
    }
        
    
    ### Abbreviations ###
    
    function stripAbbreviations($text) {
    #
    # Strips abbreviations from text, stores the URLs and titles in
    # hash references.
    #
        $less_than_tab = $this->tab_width - 1;

        # Link defs are in the form: [id]*: url "optional title"
        $text = preg_replace_callback('{
            ^[ ]{0,'.$less_than_tab.'}\*\[(.+?)\][ ]?:  # abbr_id = $1
            (.*)                    # text = $2 (no blank lines allowed)    
            }xm',
            array(&$this, '_stripAbbreviations_callback'),
            $text);
        return $text;
    }
    function _stripAbbreviations_callback($matches) {
        $abbr_word = $matches[1];
        $abbr_desc = $matches[2];
        $this->abbr_matches[] = preg_quote($abbr_word);
        $this->abbr_desciptions[$abbr_word] = trim($abbr_desc);
        return ''; # String that will replace the block
    }
    
    
    function doAbbreviations($text) {
    #
    # Replace footnote references in $text [^id] with a link to the footnote.
    #
        if ($this->abbr_matches) {
            $regex = '{(?<!\w)(?:'. implode('|', $this->abbr_matches) .')(?!\w)}';
    
            $text = preg_replace_callback($regex, 
                array(&$this, '_doAbbreviations_callback'), $text);
        }
        return $text;
    }
    function _doAbbreviations_callback($matches) {
        $abbr = $matches[0];
        if (isset($this->abbr_desciptions[$abbr])) {
            $desc = $this->abbr_desciptions[$abbr];
            if (empty($desc)) {
                return $this->hashSpan("<abbr>$abbr</abbr>");
            } else {
                $desc = $this->escapeSpecialCharsWithinTagAttributes($desc);
                return $this->hashSpan("<abbr title=\"$desc\">$abbr</abbr>");
            }
        } else {
            return $matches[0];
        }
    }

}